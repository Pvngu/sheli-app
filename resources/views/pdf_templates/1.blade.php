<!DOCTYPE html>
<html>
<head>
    <title>Client PDF</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
        }
        
        .page {
            background-color: white;
            position: relative;
            padding: 20.68pt;
        }

        .page-break {
            page-break-after: always;
        }
        
        /* ----------- Edit Mode ----------- */
        
        /* .page {
            width: 8.5in !important;
            height: 11in !important;
            margin: 1in auto !important;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #ddd !important;
            padding: 1in  !important;
        }
        
        .signature-section {
            padding-top: 20px;
        }
        
        .morning-logo {
            top: 70px !important;
            right: 82px !important;
            width: 300px !important;
        }
        
        p {
            font-size: 17px !important;
        } */
        
        /* ----------- Edit Mode ----------- */
        
        .morning-logo {
            display: block;
            position: absolute;
            top: 28px;
            right: 28px;
            width: 260px;
        }
        
        h1, h2, p, li {
            margin: 0;
            padding: 0;
            line-height: 1.5;
            padding-bottom: 0.50rem;
        }
        
        h1 {
            font-size: 14px;
            margin-bottom: 12px;
            text-decoration: underline;
        }
        
        p {
            text-align: justify;
            font-size: 14px;
            margin-bottom: 10px;
        }
    
        li {
            font-size: 14px;
            margin-bottom: -8px;
        }
    
        ol li {
            padding-left: 8px;
        }
    
        ul li {
            margin-left: 13px;
        }
        
        i {
            font-size: 11px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .container {
            text-align: right;
            margin-right: 130px;
        }
        
        .signature-section {
            padding-top: 20px;
        }
        
        .signature-section div{
            display: inline-block;
            width: 49%;
        }
        
        .signature-section p{
            line-height: 0.6rem;
        }
        
        .signature {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 20px;
        }
        
        .law-signature {
            display: inline-block;
            text-align: left;
        }
        
        .law-signature .law-image {
            height: 80px;
            margin-bottom: 4px;
        }
        
        .law-signature .law-details p{
            line-height: 0.5rem;
        }
        
        .bottom-text {
            font-size: 11px;
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        
        .date {
            float: right;
        }
        
        .pt-3 {
            padding-top: 0.75rem;
        }
    
        table {
            border-collapse: collapse;
            width: 100%;
        }
    
        th, td {
            text-align: left;
            padding: 8px;
        }
    
        tr:nth-child(even) {background-color: #f2f2f2;}

        .hide {
            color: white;
        }

        .hide-p p{
            color: white;
        }
    </style>
</head>
{{-- <img class="morning-logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/morning_law_group.webp'))) }}" alt="morning law group logo">
<img class="law-image" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/morning_law_signature.png'))) }}"> --}}
<body>
    <div class="page">
        <img class="morning-logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/morning_law_group.webp'))) }}" alt="morning law group logo">
        
        <p class="pt-3">{{ \Carbon\Carbon::now()->format('F jS, Y') }}</p>
        <p>{{ $first_name }} {{ $last_name }}<br>
        {{ $address_line1 }}<br>
        {{ $email }}</p>

        <p>Re: <u>Legal Services Aggrement</u></p>

        <p>Dear {{ $first_name }}:</p>

        <p>We are pleased that you are interested in becoming <strong>({{ $first_name }} {{ $last_name }})</strong> a client of Morning Law Group, P.C. Attached is a copy of our terms of engagement (the <strong>Terms</strong>), which are incorporated by reference into this engagement letter. This letter and our terms are referred to together as the <strong>Agreement</strong>. We will review your intake file and will confirm if we are able to represent you. Once we accept and confirm your engagement, our Services will commence. This Agreement sets out the terms of our engagement, including the anticipated scope of our services and the billing policies and practices that will apply. As more fully described in the Terms, our services are limited to representing you in connection with disputes you have with specific creditors that you have identified for us and for which we have agreed to represent you.</p>

        <p>In order to provide you with cost-effective legal representation, Morning Law Group contracts with third-party service providers to provide administrative, call center, mail processing, and file management services (<strong>Admin Services</strong>) in conjunction with your legal representation (the “<strong>Admin Provider</strong>”). The provider of the Admin Services is identified on Schedule 2 of this Agreement. The Admin Services also commence upon acceptance of your signed engagement letter by Morning Law Group and are an integral part of your legal representation and attorney-client relationship.</p>

        <p>We are pleased to be working with you and will be contacting you soon to have an introduction call. If you have any questions concerning anything outlined in this Agreement or any other aspect of our representation, please feel free to contact us.</p>
        <div class="container">
            <div class="law-signature">
                <div style="font-size: 12px;">
                    MORNING LAW GROUP, P.C.
                </div>
                <img class="law-image" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/morning_law_signature.png'))) }}">
                <div class="law-details">
                    <p>Joshua Armstrong</p>
                    <p>Managing Shareholder</p>
                </div>
            </div>
        </div>

        <p style="padding-bottom: 14px;"><i>Please indicate your agreement to this engagement letter and the Terms by signing below.</i></p>

        <div class="signature-section">
            <div>
                <div>
                    <p>Signature:</p>
                    <p>{{ $first_name }} {{ $last_name }}</p>
                    <p>Date:</p>
                </div>
                <div class="hide-p">
                    <p>{A_SIGNATURE}</p>
                    <p>.</p>
                    <p>{A_DATE}</p>
                </div>
            </div>
            @if(in_array('co_applicant', $includeArr))
                <div>
                    <div>
                        <p>Co-Applicant:</p>
                        <p>{{ $co_first_name }} {{ $co_last_name }}</p>
                        <p>Date:</p>
                    </div>
                    <div class="hide-p">
                        <p>{CO_SIGNATURE}</p>
                        <p>.</p>
                        <p>{CO_DATE}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="bottom-text">38 Technology Drive, Suite 100, Irvine, CA 92618 | clientservices@morninglawgroup.com
        </div>
    </div>
    <div class="page">
        <h1 class="text-center">Terms of Engagement</h1>
        <p>Unless otherwise stated, capitalized terms used in these Terms that are not defined in these Terms, have the meanings given to them in the accompanying engagement letter. If there is a conflict between the provisions in these Terms and the accompanying engagement letter, the provisions in the engagement letter will control</p>

        <h1>Services To Be Performed</h1>
        <p>Subject to the terms of this Agreement, we agree to perform the following legal services (the “Services”):</p>
        <ul>
            <li>Assist you in communicating with creditors and debt collectors in connection with any of the debts listed on Schedule 1 (each, a <strong>Debt</strong> and, collectively, the <strong>Debts</strong>);</li>
            <li>Assess and (if appropriate) dispute the legal validity and/or enforceability of the Debts;</li>
            <li>Assist you in disputing and addressing erroneous or inaccurate information on your credit reports related to the Debts;</li>
            <li>Represent or coordinate the representation of you if, during this Agreement, a creditor or related debt collector files a lawsuit against you related to the Debts (but excluding any appeals); and</li>
            <li>Evaluate possible violations of state or Federal law in connection with any Debt</li>
        </ul>

        <h1>Additional Servives Offered</h1>
        <p>With your consent, we may settle any Debt that is subject to active or contemplated/threatened litigation. In addition, if
            we receive any offer of settlement from a creditor or collector, we will communicate the offer to you. In these events,
            we will attempt to negotiate the most favorable settlement of the lawsuit; <strong><u>however, any such settlement shall be your
            sole responsibility and shall be paid by you directly to the settling creditor/collection agency.</u></strong>
        </p>
        <p>If we (or you) settle any Debt lawsuit, or if a judgment is entered in a Debt lawsuit, our legal services will be considered complete on that particular Debt and we will no longer be your attorney on that Debt for any purpose. This means that if you fail to make a payment to a creditor pursuant to a settlement agreement or a court judgment, and the creditor takes legal action against you, we will not assist you on that legal action unless we agree to do so in a separate signed engagement.</p>

        <h1>No Guarantees</h1>
        <p>
            <strong>We do not guarantee any Services nor any outcome in your (or any) legal matter, including that any Debt will be
            invalidated or that any lawsuit will be won or settled. We will use our best efforts to help you, but every
            person’s legal matter is different, and our ability to help you will depend on your particular situation.</strong>
        </p>

        <h1>Excluded from Services</h1>
        <p>Our representation under this Agreement does not include any of the following services:</p>
        <ul style="margin: 0;">
            <li>Bankruptcy services are not included in this Agreement. But if at any time, bankruptcy becomes the right option for you, we can discuss entering into a separate agreement for those services;</li>
        </ul>
    </div>
    <div class="page">
        <ul>
            <li>We do not provide credit repair services, advise you on your credit history or credit score, counsel you on credit habits or behavior, or any other services related to your credit score;</li>
            <li>We do not provide debt settlement services (which is different from settling lawsuits or threatened lawsuits involving your Debts);</li>
            <li>We do not negotiate with creditors or related debt collectors to try to get you a discount, reduced payment amount, or payment plan on any valid, legal debts you owe (excluding any negotiations that occur as part of resolving a lawsuit); and</li>
            <li>We do not make payments to creditors or debt collectors on your behalf for any reason.</li>
            <li>We do not provide tax, financial planning, or accounting advice (note that the discharge of debt can trigger a taxable event, so you should make an appointment with an appropriate tax professional before agreeing to MLG’s suggested resolution of a Debt lawsuit);</li>
            <li>We do not provide representation in any appeals; and</li>
            <li>Except as provided for in this Agreement, we do not provide representation in any matter before any court or arbitration hearing, including but not limited to foreclosure or eviction proceedings, or lawsuits pending against you prior to signing this Agreement (unless otherwise provided in this Agreement).</li>
        </ul>

        <p><strong>If any of these excluded services are the type of help you are looking for, <strong>DO NOT SIGN</strong> this Agreement. Instead, please contact another law firm or company for assistance.</strong></p>

        <h1>Your Authorizations</h1>

        <p>You authorize us to: (a) communicate on your behalf with creditors/debt collectors relating to the Debts; (b) obtain a
            copy of your credit report(s) to assist us in assessing your Debts and to develop a strategy regarding the Debts that are
            invalid, unauthorized, or unenforceable; (c) challenge, where applicable, each of the Debts that you believe are invalid,
            inaccurate or without legal basis; (d) to communicate with you in the manners set forth in this Agreement; and (e) act
            under any Power of Attorney or Client Authorization Letter that you sign.</p>

        <p>Our primary means of communication with you will be electronically via email, facsimile, text, direct voicemail, and messages in our client portal (where available). You authorize us and our service providers to contact you using any electronic and/or telephonic methods and that all business with us will be conducted electronically and telephonically. You also consent to us and our service providers using pre-recorded calls, automatic telephone dialing systems, and/or artificial voice messaging to the telephone number that you provide us solely to communicate with you regarding the Services and not for marketing purposes. You authorize us and our service providers to transmit data electronically, including information regarding the Debts and your credit profile. You acknowledge that any electronic communication carries the risk of disclosure to a third party and we will not be held responsible for any such disclosure.</p>
        <p>You can revoke any of the authorizations listed in this paragraph at any time by simply sending us a written request by letter or email to <a href="mailto:info@morninglawgroup.com">info@morninglawgroup.com</a>, or in response to a text message by texting “STOP”. Please note that if you revoke any of these authorizations, we may not be able to continue providing the Services and have the right to terminate this Agreement.</p>
    </div>
    <div class="page">
        <h1>Your Responsibilities</h1>
        <p>You understand and agree to the following:</p>
        <ul>
            <li>To provide us with accurate information regarding your Debts, including account numbers, balances, creditor or collector names, and all other information we require related to the Debts;</li>
            <li>To provide us with all correspondence (except regular bills) that you receive from any creditor, credit bureau, attorney or court as soon as possible, but no later than five (5) days after receiving them. Please send all such correspondence by email, if possible, to <a href="mailto:clientservices@morninglawgroup.com">clientservices@morninglawgroup.com</a>;</li>
            <li>To notify us immediately when you receive a Summons (a court document that informs you that you are being sued) or any other court document. Court documents have specific (and often short) deadlines by which responses must be filed in court and your failure to timely notify us and provide us with a copy of the Summons or other court documents may affect your legal rights and our ability to properly represent you;</li>
            <li>To notify us promptly if a creditor or collection agency engages in harassing or abusive conduct;</li>
            <li>To keep a log of all communications, including telephonic and electronic communications, from any of those parties until the conclusion of our representation of you and to provide it to us upon request. We have included with this Agreement a sample log that you can use;</li>
            <li>All information you provide us will be accurate and truthful;</li>
            <li>To provide us with accurate contact information to reach you and to promptly notify us of any changes;</li>
            <li>To make all payments set forth in Schedule 2 and to notify us promptly of any changes to your payment account information;</li>
            <li>To promptly respond to all requests or communications from us, but no later than within three (3) business days. You understand that your failure to respond in a timely manner can have significant consequences and can affect our ability to properly represent you; and</li>
            <li>To cooperate with us and to participate in your legal representation, especially when we request your participation.</li>
        </ul>

        <h1>Fees</h1>
        <p>As set forth herein, you agree to pay the fees listed on Schedule 2 (“ <strong>Fees</strong>”), which Fees are broken down into two categories, <strong>Legal Fees</strong> and <strong>Admin Fees</strong>. Although these are distinct fees for the Services you are receiving, for convenience, your monthly payment will include both the Legal Fees and the Admin Fees, which Fees will then be apportioned by the payment processor as follows: the Legal Fees will be distributed directly to Morning Law Group, P.C. and the Admin Fees will be distributed directly to the Admin Provider.</p>

        <p>The total Legal Fees represent a flat fee for our legal Services and are inclusive of any costs that we incur on your behalf, including fees for local counsel, except for the Admin Services, as explained below. Each payment shown on Schedule 2 as a Legal Fee is a portion of the Legal Fee to be paid for our Services. Our Services will begin immediately after our acknowledgement that we have accepted the engagement and our receipt of this signed Agreement so that the cost for our Services will always exceed the amount of your next scheduled payment. Therefore, the Legal Fees that you pay will not be escrowed or held in a trust account, but rather are earned by us when we receive them for the Services that we have provided. The Legal Fees do not include any payment, settlement, or judgment that you may have to pay to any creditor or collection agency at any time.</p>
    </div>
    <div class="page">
        <p>Additionally, you understand that if the Admin Services were to be handled in-house by the law firm, the cost of your legal representation would be significantly higher. Accordingly, to maintain the affordable nature of these Admin Services, the Admin Fees will be paid directly to the Admin Provider by the payment processor. The Admin Fees are listed on Schedule 2</p>
        <p>If you request (and we agree) to represent you on any pre-existing lawsuit (a lawsuit pending against you prior to this Agreement), you must first pay an additional flat fee of $1,000.00. This additional fee is required because assuming legal representation in an existing lawsuit is beyond the initial scope of the services being provided and requires extra time and resources to review and get quickly up to speed to enable us to meet court deadlines that have already been set. Also, if you request, and we agree, to represent you on any other accounts not included in the Debts, that representation will be subject to additional fees.</p>

        <p>Because our Services are provided at a significantly discounted rate, if a court awards attorneys’ fees against an adverse party in any lawsuit that we, or our contracted local counsel, initiate on your behalf under consumer protection laws or any lawsuit involving a Debt, that fee award will belong to the Firm and will be shared with the local counsel, as applicable.</p>

        <p>The Legal Fees and the Admin Fees do NOT include any amount that you must pay to any creditor/collection agency on any Debt, judgment, or settlement. As explained previously, this Firm does NOT make payments to creditors/collection agencies on your behalf.</p>

        <h1>Secured Debts</h1>
        <p>A secured debt is subject to and governed by a security interest, such as a car loan, in which you agree that a lender has a right to repossess the vehicle or other secured item if you do not pay. You understand that any secured debt item may be repossessed or you may otherwise permanently lose possession of the item while we are challenging and disputing the underlying debt. We do not guarantee that we will be able to challenge all of your Debts, whether those debts are secured (the creditor has a right to foreclose) or unsecured (the creditor has no such right).</p>

        <h1>Malpractice Insurance</h1>
        <p>We maintain a malpractice insurance policy that covers and insures our representation of you. If you desire to make a claim against that insurance policy, you must first contact us and disclose your claim and the nature of the claim, at which point we agree to assist you in obtaining the information necessary to file a claim. Any claim brought against us must be brought within six (6) months of the claimed offense.</p>

        <h1>Applicable Law</h1>
        <p>Although our attorneys are located in several states, our firm is a California corporation. To the extent permitted by law, you and the Firm agree that, except as provided below, the Services are provided in California and California law applies to this Agreement. You specifically acknowledge that you are agreeing to contract with the Firm in California. To assist our clients, we work with attorneys licensed in all fifty states and the District of Columbia including members and associates of our Firm. With respect to any aspect of our representation of you that occurs outside of California, we will help secure local counsel on your behalf, and you authorize us to retain, and replace as needed, such local counsel. Upon request, we will provide you with a list of all local counsel working on your behalf. Please note that some local counsel may need you to sign a no-fee separate engagement with them to ensure that they can legally represent you if a lawsuit occurs. If you do not sign their engagement, they may not be able to represent you in the lawsuit.</p>
    </div>
    <div class="page">
        <h1>Fee Disputes and Resolution</h1>
        <p>If you are a California resident and wish to dispute the Fees owed to MLG, you have the option of asking for mandatory fee arbitration with the appropriate local bar association program. You must initiate the mandatory fee arbitration within the applicable period allowed in which to bring a civil action for judicial resolution of a fee dispute. If MLG claims that you owe Fees that you dispute, we will provide to you a “Notice of Client’s Right to Fee Arbitration” form approved by the California State Bar before or at the time of filing a lawsuit or other proceeding to collect the Fees. You will then have the option of asking for mandatory fee arbitration with the appropriate local bar association program within 30 days of receiving that notice, or with the California State Bar if the appropriate local bar association does not offer mandatory fee arbitration. If you fail to request the mandatory fee arbitration within 30 days, you will have waived your right to arbitration, and MLG may take legal action to collect the fees or costs. The arbitrator will not have the power to commit errors of law or legal reasoning, and the award may be vacated or corrected on appeal to a court of competent jurisdiction, for any such error. You should consult independent legal counsel if you have a question about your rights pertaining to a potential fee dispute or arbitration with MLG.</p>
        <p>Per California law, any arbitration result will be non-binding unless you and MLG both agree that the arbitration is binding, in which case the results are final. Depending upon the amount of the Fee dispute, the arbitration panel will consist of either a single attorney arbitrator or a panel of three arbitrators, one member of which is not an attorney. Arbitrators are usually chosen by an independent arbitration association and are impartial as well as being familiar with the areas of law and/or facts involved in the dispute. Should there be a dispute regarding fees, the law of California shall apply in arbitration as well as civil litigation. If You are not a California resident, the laws and rules of your state of residence, including any arbitration or alternative dispute resolution laws or options, will apply in any fee dispute.</p>
        <p>You and the Firm mutually acknowledge that, by this agreement to arbitrate, you and the Firm each irrevocably waive the right to a court or a jury trial.</p>

        <h1>Termination</h1>
        <p>You have the right to terminate this Agreement and our Services at any time by providing us a written notice, such as a letter or an email. The termination will take effect on the date we receive the written notice; however, we will still process any payment due within five (5) business days after receipt of your written notice, which payment is for any Services rendered prior to the termination and for our costs of processing the termination, withdrawing from any cases, and closing your file. Your written notice of termination must be sent by email to: <a href="mailto:clientservices@morninglawgroup.com">clientservices@morninglawgroup.com</a> with the subject: “Terminate Services”, or by mail to our address listed on the bottom of this Agreement.</p>
        <p>We have the right to terminate this Agreement and our Services (except to the extent limited by applicable law or rules of professional conduct) at any time for any reason, after giving you reasonable notice of our decision to terminate. Without limiting the foregoing, some circumstances in which we may terminate include if:</p>
        <ul>
            <li>Required or permitted by the applicable rules of professional conduct;</li>
            <li>We complete the Services, such as by invalidating the Debts;</li>
            <li>We determine that the Debts are valid and therefore cannot be legally challenged;</li>
            <li>You fail to communicate with us, fail to provide us with information necessary to represent you in a timely manner, fail to cooperate with us in your representation, or engage in harassing or abusive behavior towards any member of our team;</li>
            <li>You fail to make timely payments of our Fees (including any returned payments) as set out in these Terms;</li>
            <li>Your payments to us are returned or fail to process more than two times for any reason (such as for insufficient funds or account errors or account closed);</li>
            <li>You fail to comply with any of your other responsibilities set forth in this Agreement; or</li>
            <li>You revoke any of the authorizations provided in this Agreement.</li>
        </ul>
    </div>
    <div class="page page-break">
        <p>We will notify you by email if we terminate this Agreement. The termination will take effect on the date of the notice; however, we will still process any payment due within five (5) business days after the notice date, which payment is for any Services rendered prior to the termination and for our costs of closing your file. If you have paid any fees that exceed the amount of any final payment due to MLG under this Agreement, we will refund that excess amount to You. We will also notify the creditors and collectors (or their counsel) on your Debts that we no longer represent you so that you may communicate with them directly.</p>
        <h1>Entire Agreement</h1>
        <p>This Agreement constitutes the entire Agreement between us relating to our Services. Nothing that anyone has said to you before you sign this Agreement that relates in any way to our Firm or our services or that described, represented, or guaranteed what our Firm can or will do, whether such statements were made orally or in writing and whether made by any member of our Firm or any agents, affiliates, or other person, will become a part of this Agreement unless written into this Agreement expressly. This Agreement may not be modified except in writing signed by both you and us. A facsimile or digitally signed version of this Agreement will be as valid as an original signed Agreement.</p>

        <h1>Your Acknowledgement</h1>
        <p>By signing this agreement, you confirm and acknowledge that:</p>
        <ol>
            <li>Morning Law Group is a law firm providing you with legal services; it is not a credit repair organization nor a debt settlement company;</li>
            <li>MLG has not instructed you to breach any contract, to stop making or fail to make any required payment on any debts, or to fail to perform any obligation you have lawfully incurred. You understand that failing to make a payment on any debt may adversely affect your credit and you will continue to incur late fees and penalties;</li>
            <li>All fees paid under this Agreement are for MLG’s legal services and the associated administrative services. Your monthly payments are collected by the payment processor and then split into two distributions, with a portion paid to MLG and a portion paid to the Admin Provider;</li>
            <li>None of the fees are used to pay any of your Debts. You are solely responsible for paying your Debts, any damages awarded against you in any lawsuit, and/or any settlement amounts pursuant to any legal settlement entered into with a creditor or collection agency;</li>
            <li>MLG cannot guarantee that any enrolled Debt will be invalidated, resolved, or that any lawsuits will be settled or won through its efforts.</li>
            <li>If, during this Agreement, a creditor or related debt collector files a lawsuit against you related to an enrolled Debt, MLG will represent or coordinate the representation of you at no additional cost to you. Once the lawsuit is settled or a judgment is entered, the legal representation on that enrolled Debt will end; MLG will not represent you in any appeal;</li>
            <li>You understand that if any of the enrolled debts involve auto loans, are secured, or have any type of collateral, those items may be repossessed.</li>
            <li>You will comply with your responsibilities as set forth in this Agreement; and</li>
            <li>You have read and understand this Agreement and are entering into it after full investigation and are not relying upon any statement or representation from our Firm or any other person that is not written in this Agreement.</li>
        </ol>

        <div class="signature-section">
            <div>
                <div>
                    <p>Signature:</p>
                    <p>{{ $first_name }} {{ $last_name }}</p>
                    <p>Date:</p>
                </div>
                <div class="hide-p">
                    <p>{A_SIGNATURE}</p>
                    <p>.</p>
                    <p>{A_DATE}</p>
                </div>
            </div>
            @if(in_array('co_applicant', $includeArr))
                <div>
                    <div>
                        <p>Co-Applicant:</p>
                        <p>{{ $co_first_name }} {{ $co_last_name }}</p>
                        <p>Date:</p>
                    </div>
                    <div class="hide-p">
                        <p>{CO_SIGNATURE}</p>
                        <p>.</p>
                        <p>{CO_DATE}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(in_array('debts', $includeArr))
    <div class="page page-break">
        <p>Schedule 1</p>
        <h1>Enrolled Accounts</h1>
        <table>
            <tr>
                <td style="width: 60%;">Creditor</td>
                <td>Account #</td>
                <td>Debt Balance</td>
            </tr>
            @foreach($debts as $debt)
                <tr>
                    <td>{{ $debt->creditor->name }}</td>
                    <td>{{ $debt->account }}</td>
                    <td>${{ number_format($debt->current_amount, 2, '.', ',') }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td><strong>${{ number_format($total_debt, 2, '.', ',') }}</strong></td>
            </tr>
        </table>
    </div>
    @endif
    @if(in_array('payment_schedule', $includeArr))
    <div class="page page-break">
        <p>Schedule 2</p>
        <h1>Schedule of Payments</h1>
        <table>
            <tr>
                <td>#</td>
                <td>Date</td>
                <td>Interest</td>
                <td>Principal</td>
                <td>Balance</td>
            </tr>
            @foreach($payment_schedules as $index => $payment_schedule)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ Carbon\Carbon::parse($payment_schedule->due_date)->format('M d, Y') }}</td>
                    <td>${{ number_format($payment_schedule->interest_amount, 2, '.', '.') }}</td>
                    <td>${{ number_format($payment_schedule->principal_amount, 2, '.', '.') }}</td>
                    <td>${{ number_format($payment_schedule->balance_amount, 2, '.', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td><strong>${{ number_format($total_interest, 2, '.', ',') }}</strong></td>
                <td><strong>${{ number_format($total_principal, 2, '.', ',') }}</strong></td>
                <td></td>
            </tr>
        </table>
    </div>
    @endif
    <div class="page">
        @if(in_array('bank', $includeArr))
        <h1>DESIGNATED BANK ACCOUNT INFORMATION</h1>
        <p><strong>Address:</strong> {{ $account_holder_address }}<br>
        <strong>City:</strong> {{ $account_holder_city }}<br>
        <strong>State:</strong> {{ $account_holder_state }}<br>
        <strong>Zip:</strong> {{ $account_holder_zipcode }}<br>
        <strong>Mobile Phone #:</strong> {{ $phone_number }}<br>
        <strong>E-mail:</strong> <a href="mailto:jmaehill6@gmail.com">{{ $email }}</a></p>

        <p><strong>Preauthorized Checking and ACH Authorization Form</strong></p>
        <p><strong>Bank Name:</strong> {{ $bank_name }} <br>
        <strong>Name as it appears on bank ACCOUNT:</strong> {{ $first_name }} {{ $last_name }}<br>
        <strong>Routing Number:</strong> {{ $routing_number }}<br>
        <strong>Account Number:</strong> {{ $account_number }}<br>
        <strong>Checking or Saving:</strong> {{ $payment_method }}</p>
        @endif
        
        @if(in_array(['debts', 'payment_schedule'], $includeArr))
            <p><strong>DESIGNATED ACCOUNT PAYMENT AUTHORIZATION SCHEDULE</strong></p>
            <p><strong>Total Amount of Debit:</strong> {{ $total_debt }} <br>
            <strong>Date of Next Debit:</strong> {{ $next_payment_date }}</p>
        @endif

        <p>By signing below, I authorize and permit MLG or its designees, including without limitation, Payment Automation Network, to initiate electronic funds transfer via an Automated Clearing House system (ACH) or Electronic Funds Transfer (EFT) or Remotely Created Check (RCC) from my designated bank account at the financial institution listed above, or from any different account that I instruct MLG in writing to use. I will also provide MLG with a voided check or savings deposit slip.</p>

        <p>I authorize the debits to my bank account to be made according to the schedule of payments provided by this Agreement. I understand that debits will be withdrawn on the due date, or as soon thereafter as possible unless otherwise indicated, and that sufficient funds must be available in the designated account at least two (2) business days prior to the actual date of the debit.</p>

        <p>Upon my approval, MLG or its designees may adjust the amount being debited from designated bank account. A payment reminder may be sent to my email or to my phone number via text messaging or auto-dialed direct messaging prior to the payment draw date.</p>

        <p>If necessary, MLG shall make adjustments if errors have occurred during the transaction. The monthly date of the draft is listed above; however, if the draft date falls on a weekend or bank holiday, the debit transaction will take place on the next business day. This authority will remain in effect until five (5) business days after the date that MLG receives from You written notice of termination of this authorization. If the debit is returned because of insufficient funds or uncollected funds, then the originator and its financial institution may reinitiate the entry up to two (2) times. Further, in such event, You authorize MLG or its designee to recover funds by ACH/EFT/RCC debit from Your bank account and agree that a fee of $25.00, or the amount allowed by law if less than $25.00, may be charged to Your account via draft/ACH/EFT, if permitted by applicable laws and state bar rules.</p>
    </div>
    <div class="page">
        <h1>Acknowledgment of Refunds & Draft Date Changes</h1>
        <p>ACH/EFT/RCC Refunds: If a refund is due to You, such refund will be made through the ACH/EFT/RCC process only. Refunds may take up to 10 days to process due to bank procedures that are outside MLG’s control. Draft Date Changes: You may stop any ACH debit if MLG receives written notice from You of termination of this Authorization at least five (5) business days prior to the scheduled payment. If You should need to notify MLG of Your intent to terminate and/or revoke this authorization, You must notify MLG in writing not fewer than five (5) business days prior to the questioned debit being initiated. Notice of termination shall be sent to MLG directly at the address set forth in the Agreement.</p>

        <p>I have read and understand the information contained in this document and I affirm that the above information given by me is accurate and true to the best of my knowledge.</p>

        <p><strong>Client Signature:</strong><br>
        <strong>Date:</strong><br>
        <strong>Printed Name:</strong>{{ $first_name }} {{ $last_name }}</p>

        <p><em>*Please note that in the event of unforeseen circumstances or technical issues, payments may not be processed on the exact date listed above, but will be processed as soon as reasonably possible thereafter.</em></p>
    </div>
    <div class="page">
        <h1>Client Authorization Letter</h1>
        <p><strong>Name:</strong> {{ $first_name }} {{ $last_name }}<br>
        <strong>Social Security No:</strong> XXX-XX-{{ substr($SSN, -4) }}<br>
        <strong>Date of Birth:</strong> {{ $date_of_birth }}<br>
        <strong>Address:</strong> {{ $address_line1 }}, {{ $city }}, {{ $state }} {{ $zip_code }}<br>
        @if(in_array('co_applicant', $includeArr))
        <strong>Co-App Name:</strong> {{ $co_first_name }} {{ $co_last_name }}<br>
        <strong>Co-App Social Security No:</strong> XXX-XX-{{ substr($co_SSN, -4) }}<br>
        <strong>Co-App Date of Birth:</strong> {{ $co_date_of_birth }}</p>
        @endif

        <p>To Whom It May Concern:</p>
        <p>Please be advised that I am represented by Morning Law Group, P.C., a law firm, to help me with my debts. As my attorneys, I give them my full authority to request, receive, and discuss information about the account that you claim I am responsible for, including my personal financial and credit information. I authorize you to release to Morning Law Group all information and documents that they may request. They also have my full authority to communicate with you and all credit bureaus on my behalf, to dispute any invalid or incorrect information, and to represent me in any legal proceedings or collection actions relating to the purported accounts.</p>

        <p>Please direct any future communications regarding the accounts that you claim I am responsible for to my attorneys at the below address, including the results of investigations, but excluding any billing statements, invoices, or payment notices, which should continue to be sent directly to me.</p>

        <p>Morning Law Group, PC<br>
        P.O Box 513860<br>
        Los Angeles, CA 90051-3860</p>

        <p>If you have any questions, please contact Morning Law Group directly at 949-484-8988.</p>

        <div class="signature-section">
            <div>
                <div>
                    <p>Signature:</p>
                    <p>{{ $first_name }} {{ $last_name }}</p>
                    <p>Date:</p>
                </div>
                <div class="hide-p">
                    <p>{A_SIGNATURE}</p>
                    <p>.</p>
                    <p>{A_DATE}</p>
                </div>
            </div>
            @if(in_array('co_applicant', $includeArr))
                <div>
                    <div>
                        <p>Co-Applicant:</p>
                        <p>{{ $co_first_name }} {{ $co_last_name }}</p>
                        <p>Date:</p>
                    </div>
                    <div class="hide-p">
                        <p>{CO_SIGNATURE}</p>
                        <p>.</p>
                        <p>{CO_DATE}</p>
                    </div>
                </div>
            @endif
        </div>

        <p>Please indicate your agreement to this engagement letter and the Terms by signing below</p>
    </div>
</body>
</html>