<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Sale;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Settings;
use App\Models\Individual;
use App\Models\SaleStatus;
use App\Models\CoApplicant;
use App\Models\StaffMember;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Scopes\CompanyScope;
use App\Models\IndividualLog;
use App\Models\AppNotification;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Examyou\RestAPI\Exceptions\ApiException;


class Common
{
    public static function getFolderPath($type = null)
    {
        $paths = [
            'companyLogoPath' => 'companies',
            'userImagePath' => 'users',
            'langImagePath' => 'langs',
            'expenseBillPath' => 'expenses',
            'productLogoPath' => 'product',
            'audioFilesPath' => 'audio',
            'websiteImagePath' => 'website',
            'offlineRequestDocumentPath' => 'offline-requests',
            'docFilesPath' => 'documents',
            'auditFilesPath' => 'audits',
            'recordingAudioPath' => 'calls',
        ];

        return ($type == null) ? $paths : $paths[$type];
    }

    public static function uploadFile($request)
    {
        $folder = $request->folder;
        $folderString = "";

        if ($folder == "user") {
            $folderString = "userImagePath";
        } else if ($folder == "company") {
            $folderString = "companyLogoPath";
        } else if ($folder == "langs") {
            $folderString = "langImagePath";
        } else if ($folder == "expenses") {
            $folderString = "expenseBillPath";
        } else if ($folder == "product") {
            $folderString = "productLogoPath";
        } else if ($folder == "website") {
            $folderString = "websiteImagePath";
        } else if ($folder == "offline-requests") {
            $folderString = "offlineRequestDocumentPath";
        } else if ($folder == "documents") {
            $folderString = "docFilesPath";
        } else if ($folder == "calls") {
            $folderString = "recordingAudioPath";
        } else if ($folder == "audits") {
            $folderString = "auditFilesPath";
        }

        $folderPath = self::getFolderPath($folderString);

        if ($request->hasFile('image') || $request->hasFile('file')) {
            $largeLogo  = $request->hasFile('image') ? $request->file('image') : $request->file('file');

            $fileName   = $folder . '_' . strtolower(Str::random(20)) . '.' . $largeLogo->getClientOriginalExtension();
            $largeLogo->storePubliclyAs($folderPath, $fileName);
        }

        return [
            'file' => $fileName,
            'file_url' => self::getFileUrl($folderPath, $fileName),
        ];
    }

    public static function checkFileExists($folderString, $fileName)
    {
        $folderPath = self::getFolderPath($folderString);

        $fullPath = $folderPath . '/' . $fileName;

        return Storage::exists($fullPath);
    }

    public static function getFileUrl($folderPath, $fileName)
    {
        if (config('filesystems.default') == 's3') {
            $path = $folderPath . '/' . $fileName;

            return Storage::url($path);
        } else {
            $path =  'uploads/' . $folderPath . '/' . $fileName;

            return asset($path);
        }
    }

    public static function moduleInformations()
    {
        $allModules = Module::all();
        $allEnabledModules = Module::allEnabled();
        $installedModules = [];
        $enabledModules = [];

        foreach ($allModules as $key => $allModule) {
            $modulePath = $allModule->getPath();
            $versionFileName = 'version.txt';
            $version = File::get($modulePath . '/' . $versionFileName);

            $installedModules[] = [
                'verified_name' => $key,
                'current_version' => preg_replace("/\r|\n/", "", $version)
            ];
        }

        foreach ($allEnabledModules as $allEnabledModuleKey => $allEnabledModule) {
            $enabledModules[] = $allEnabledModuleKey;
        }

        return [
            'installed_modules' => $installedModules,
            'enabled_modules' => $enabledModules,
        ];
    }

    public static function getIdFromHash($hash)
    {
        if ($hash != "") {
            $convertedId = Hashids::decode($hash);
            $id = $convertedId[0];

            return $id;
        }

        return $hash;
    }

    public static function getHashFromId($id)
    {
        $id = Hashids::encode($id);

        return $id;
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function calculateTotalUsers($companyId, $update = false)
    {
        $totalUsers =  StaffMember::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $companyId)
            ->count('id');

        if ($update) {
            DB::table('companies')
                ->where('id', $companyId)
                ->update([
                    'total_users' => $totalUsers
                ]);
        }


        return $totalUsers;
    }

    public static function addWebsiteImageUrl($settingData, $keyName)
    {
        if ($settingData && array_key_exists($keyName, $settingData)) {
            if ($settingData[$keyName] != '') {
                $imagePath = self::getFolderPath('websiteImagePath');

                $settingData[$keyName . '_url'] = Common::getFileUrl($imagePath, $settingData[$keyName]);
            } else {
                $settingData[$keyName] = null;
                $settingData[$keyName . '_url'] = asset('images/website.png');
            }
        }

        return $settingData;
    }

    public static function convertToCollection($data)
    {
        $data = collect($data)->map(function ($item) {
            return (object) $item;
        });

        return $data;
    }

    public static function checkSubscriptionModuleVisibility($itemType)
    {
        $visible = true;

        return $visible;
    }

    public static function allVisibleSubscriptionModules()
    {
        $visibleSubscriptionModules = [];

        if (self::checkSubscriptionModuleVisibility('user')) {
            $visibleSubscriptionModules[] = 'user';
        }

        return $visibleSubscriptionModules;
    }

    public static function insertInitSettings($company)
    {       
        $company->is_global = 0;
        $local = new Settings();
        $local->company_id = $company->id;
        $local->setting_type = 'storage';
        $local->name = 'Local';
        $local->name_key = 'local';
        $local->status = true;
        $local->is_global = $company->is_global;
        $local->save();

        $aws = new Settings();
        $aws->company_id = $company->id;
        $aws->setting_type = 'storage';
        $aws->name = 'AWS';
        $aws->name_key = 'aws';
        $aws->credentials = [
            'driver' => 's3',
            'key' => '',
            'secret' => '',
            'region' => '',
            'bucket' => '',

        ];
        $aws->is_global = $company->is_global;
        $aws->save();

        $smtp = new Settings();
        $smtp->company_id = $company->id;
        $smtp->setting_type = 'email';
        $smtp->name = 'SMTP';
        $smtp->name_key = 'smtp';
        $smtp->credentials = [
            'from_name' => '',
            'from_email' => '',
            'host' => '',
            'port' => '',
            'encryption' => '',
            'username' => '',
            'password' => '',

        ];
        $smtp->is_global = $company->is_global;
        $smtp->save();

        $sendMailSettings = new Settings();
        $sendMailSettings->company_id = $company->id;
        $sendMailSettings->setting_type = 'send_mail_settings';
        $sendMailSettings->name = 'Send mail to company';
        $sendMailSettings->name_key = 'company';
        $sendMailSettings->credentials = [];
        $sendMailSettings->save();

        // Create Menu Setting
        $setting = new Settings();
        $setting->company_id = $company->id;
        $setting->setting_type = 'shortcut_menus';
        $setting->name = 'Add Menu';
        $setting->name_key = 'shortcut_menus';
        $setting->credentials = [
            'user',
            'language',
            'role',
        ];
        $setting->status = 1;
        $setting->save();

        // Seed for quotations
        NotificationSeed::seedAllModulesNotifications($company->id);
    }

    public static function recalculateCampaignLeads($campaignId)
    {
        $totalLeads = Lead::join('individuals', 'leads.individual_id', '=', 'individuals.id')
                          ->where('individuals.campaign_id', $campaignId)->count();

        $notStartedLeads = Lead::join('individuals', 'leads.individual_id', '=', 'individuals.id')
            ->where('individuals.campaign_id', $campaignId)
            ->where('leads.started', '=', 0)
            ->count();

        $campaign = Campaign::find($campaignId);
        $campaign->total_leads = $totalLeads;
        $campaign->remaining_leads = $notStartedLeads;
        $campaign->save();
    }

    public static function UpdateLeadTimer() {
        $loggedUser = user();
        $request = request();
        $callLogXId = $request->call_log_id;
        $callLogId = Common::getIdFromHash($callLogXId);
        $timeTaken = $request->call_time;

        $leadXId = $request->x_lead_id;
        $leadId = Common::getIdFromHash($leadXId);
        $lead = Lead::find($leadId);

        $individual = ($lead && isset($lead->individual)) ? Individual::find($lead->individual->id) : new Individual();

        $individualCallLog = IndividualLog::where('id', $callLogId)
            ->where('user_id', $loggedUser->id)
            ->first();

        if ($individualCallLog) {
            $individualCallLog->time_taken = (int)$timeTaken - (int)$individualCallLog->started_on;
            $individualCallLog->save();
        }

        $recalculateLeadTime = IndividualLog::where('individual_id', $individual->id)
            ->where('user_id', '=', $loggedUser->id)
            ->where('log_type', '=', 'call_log')
            ->sum('time_taken');

        $individual->last_action_by = $loggedUser->id;

        $individual->time_taken = $recalculateLeadTime;

        $individual->save();

        return $lead;
    }

    public static function saveIndividualdata($type)
    {
        $loggedUser = user();
        $request = request();

        // Sale
        $saleLeadXId = $request->x_sale_lead_id;
        $saleLeadId = Common::getIdFromHash($saleLeadXId);
        $saleLead = $type == 'sale' ? Sale::find($saleLeadId) : Lead::find($saleLeadId);

        $individual = ($saleLead && isset($saleLead->individual)) ? Individual::find($saleLead->individual->id) : new Individual();

        if($type == 'sale') {
            if (!$loggedUser->ability('admin', 'sales_edit')) {
                throw new ApiException("Not Allowed");
            }

            if($request->has('sale_status_id')) {
                $saleLead->sale_status_id = $request->sale_status_id;
            }
    
            if($request->has('assigned_user_xid')) {
                $assignedToXId = $request->assigned_user_xid;
                $assignedToId = Common::getIdFromHash($assignedToXId);
                $saleLead->assigned_to = $assignedToId;
            }
    
            if($saleLead->isDirty()) {
                if($saleLead->isDirty('sale_status_id')) {
                    $newValue = SaleStatus::find($request->sale_status_id);
    
                    if($newValue) {
                        $newValue = $newValue->name;
                        
                        $notes = json_encode([
                            'new_value' => $newValue,
                        ]);
                        Common::storeIndividualLog($individual->id, 'updated_sale_status', $notes);
                    }
                }
        
                if($saleLead->isDirty('assigned_to')) {
                    $oldValue = User::find($saleLead->getOriginal('assigned_to'));
                    $newValue = User::find($saleLead->assigned_to);
    
                    if($oldValue && $newValue) {
                        $oldValue = $oldValue->name;
                        $newValue = $newValue->name;
                        
                        $notes = json_encode([
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ]);
                        
                        Common::storeIndividualLog($individual->id, 'updated_sale_assigned', $notes);
                    }
                }
            }
    
            $saleLead->save();
        }

        // Applicant/Individual
        $fields = [
            'sale_data',
            'reference_number',
            'first_name',
            'last_name',
            'SSN',
            'date_of_birth',
            'home_phone',
            'phone_number',
            'email',
            'language',
            'original_profile_id',
            'lead_data',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $value = $request->$field;
                $individual->$field = in_array($field, ['first_name', 'last_name']) ? ucwords($value) : $value;
            }
        };

        // Co-applicant
        $coApplicantFields = [
            'co_first_name' => 'first_name',
            'co_last_name' => 'last_name',
            'co_SSN' => 'SSN',
            'co_date_of_birth' => 'date_of_birth',
            'co_home_phone' => 'home_phone',
            'co_phone_number' => 'phone_number',
            'co_email' => 'email',
            'co_language' => 'language',
        ];

        if(isset($request->co_first_name) && isset($request->co_last_name)) {
            // Check if co-applicant exists or not
            $coApplicant = $individual->coApplicant ?? new CoApplicant;

            $coChanges = [];

            foreach ($coApplicantFields as $field => $value) {
                if ($request->has($field)) {
                    $valueR = $request->$field;
                    $coApplicant->$value = in_array($value, ['first_name', 'last_name']) ? ucwords($valueR) : $valueR;
                }
            };

            // Store Co-Applicant Log
            if(!$individual->coApplicant) {
                $coApplicant->individual_id = $individual->id;
                
                $coApplicantNotes = [];

                foreach ($coApplicantFields as $field => $key) {
                    if ($request->filled($field)) {
                        $coApplicantNotes[$key] = in_array($key, ['first_name', 'last_name']) ? ucwords($request->$field) : $request->$field;
                    }
                }

                $coApplicantNotes = json_encode($coApplicantNotes);

                Common::storeIndividualLog($individual->id, 'co_applicant', $coApplicantNotes);
            } else {
                $updatedCoApplicantData = Arr::except($coApplicant->getDirty(), ['date_of_birth']);

                if($updatedCoApplicantData) {
                    $originalCoData = $coApplicant->getOriginal();

                    foreach($updatedCoApplicantData as $field => $newValue) {
                        $coChanges[$field] = [
                            'old_value' => $originalCoData[$field],
                            'new_value' => $newValue,
                        ];
                    }
                }
            }

            $coApplicant->save();
        }

        $updatedData = $type == 'sale' ? Arr::except($individual->getDirty(), ['date_of_birth']) : Arr::except($individual->getDirty(), ['time_taken', 'date_of_birth']);
        $changes = [];

        if($updatedData) {
            $originalData = $individual->getOriginal();

            foreach ($updatedData as $field => $newValue) {
                if($field !== 'address_id' && $field !== 'co_applicant_id' && $field !== 'lead_data') {
                    $changes[$field] = [
                        'old_value' => $originalData[$field],
                        'new_value' => $newValue,
                    ];
                }
            }
        }

        $notes = json_encode($changes);

        if($notes !== '[]') {
            Common::storeIndividualLog($individual->id, 'updated_lead', $notes);
        }

        if($type == 'lead') {
            if ($request->has('lead_status')) {
                $saleLead->lead_status = $request->has('lead_status') && $request->lead_status != '' ? $request->lead_status : null;
            }

            $individual->last_action_by = $loggedUser->id;

            $saleLead->save();

            $campaign = Campaign::find($individual->campaign_id);
            $campaign->last_action_by = $loggedUser->id;
            $campaign->save();
        }

        $individual->save();

        return $saleLead;
    }

    public static function takeLeadAction($actionType, $leadId, $campaignId)
    {
        $user = user();
        $lead = null;

        if ($actionType == 'next') {
            // Check if next lead exists or not
            $lead = Lead::join('individuals', 'leads.individual_id', '=', 'individuals.id')
                        ->where('leads.id', '>', $leadId)
                        ->where('individuals.last_action_by', $user->id)
                        ->where('individuals.campaign_id', $campaignId)
                        ->orderBy('leads.id', 'asc')
                        ->select('leads.id')
                        ->first();


            if (!$lead) {
                // Getting next not started lead
                $lead = Lead::join('individuals', 'leads.individual_id', '=', 'individuals.id')
                            ->where('leads.id', '>', $leadId)
                            ->where('individuals.campaign_id', $campaignId)
                            ->where('leads.started', 0)
                            ->orderBy('leads.id', 'asc')
                            ->select('leads.id')
                            ->first();

                if ($lead) {
                    $individual = Individual::find($lead->id);

                    // It is new Lead so saving first actioner
                    $individual->first_action_by = $user->id;
                    $individual->last_action_by = $user->id;
                    $lead->started = 1;

                    $individual->save();
                    $lead->save();

                    // Calculating Lead Counts
                    Common::recalculateCampaignLeads($campaignId);
                }
            }
        } else if ($actionType == 'previous') {
            $lead = Lead::join('individuals', 'leads.individual_id', '=', 'individuals.id')
                ->where('leads.id', '<', $leadId)
                ->where('individuals.last_action_by', $user->id)
                ->where('individuals.campaign_id', $campaignId)
                ->orderBy('leads.id', 'desc')
                ->select('leads.id')
                ->first();
        }

        return $lead;
    }

    public static function updateBookingStatus($bookingType, $individualId, $bookingStatus)
    {
        $individual = null;

        if ($bookingType == 'lead_follow_up' || $bookingType == 'salesman_bookings') {
            $individual = Individual::find($individualId);
            $logId = $bookingType == 'lead_follow_up' ? $individual->individual_follow_up_id : $individual->salesman_booking_id;
            if ($bookingType == 'lead_follow_up') {
                $individual->individual_follow_up_id = null;
            } else {
                $individual->salesman_booking_id = null;
            }
            $individual->save();

            if (!is_null($logId)) {
                $IndividualLog = IndividualLog::find($logId);
                $IndividualLog->booking_status = $bookingStatus;
                $IndividualLog->save();
            }
        }

        return $individual;
    }

    public static function storeIndividualLog($individualId, $logType, $notes = null)
    {
        $loggedUser = user();

        $IndividualLog = new IndividualLog();
        $IndividualLog->individual_id = $individualId;
        $IndividualLog->log_type = $logType;
        $IndividualLog->user_id = $loggedUser->id;
        if(isset($notes)) {
            $IndividualLog->notes = $notes;
        }
        $IndividualLog->date_time = Carbon::now()->setTimezone('America/Los_Angeles');
        $IndividualLog->save();

        return $IndividualLog;
    }

    public static function createNotification($type, $receiverId = null, $individualId = null, $enrollmentId = null, $documentId = null, $individualLogId = null, $senderId = null, $extra = null) {
        $notification = new AppNotification();
        $notification->type = $type;

        $fields = [
            'individual_id' => $individualId,
            'enrollment_id' => $enrollmentId,
            'document_id' => $documentId,
            'individual_log_id' => $individualLogId,
            'sender_id' => $senderId,
            'extra' => $extra,
        ];
        
        foreach ($fields as $field => $value) {
            if ($value) {
                $notification->$field = $value;
            }
        }

        $notification->save();

        $notification->user()->attach($receiverId);
    }
}
