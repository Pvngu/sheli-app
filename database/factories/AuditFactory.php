<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition()
    {
        return [
            'audit_name' => $this->generateFindings('name'),
            'audit_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'auditor_id' => $this->faker->numberBetween(102, 111),
            'area_id' => $this->faker->numberBetween(1, 10),
            'findings' => $this->generateFindings('findings'),
            'corrective_actions' => $this->generateCorrectiveActions(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'in-progress']),
        ];
    }

    private function generateFindings($column)
    {
        $findings = [
            'Unsafe working conditions' => 'The working environment poses significant risks to employee safety, including exposure to hazardous materials and unsafe machinery.',
            'Lack of proper training' => 'Employees have not received adequate training to perform their tasks safely and efficiently, leading to increased risk of accidents and errors.',
            'Poor equipment maintenance' => 'Equipment and machinery are not regularly maintained, resulting in frequent breakdowns and potential safety hazards.',
            'Inadequate safety protocols' => 'The current safety protocols are insufficient to protect employees from workplace hazards, and there is a lack of enforcement.',
            'Failure to follow procedures' => 'Employees are not adhering to established procedures, which compromises safety and efficiency.',
            'Communication breakdowns' => 'There are significant communication issues within the team, leading to misunderstandings and errors.',
            'Inefficient workflow processes' => 'The current workflow processes are inefficient, causing delays and reducing overall productivity.',
            'Non-compliance with regulations' => 'The organization is not in compliance with industry regulations and standards, which could result in legal and financial penalties.',
            'Lack of supervision and oversight' => 'There is insufficient supervision and oversight, leading to a lack of accountability and increased risk of errors.',
            'Inadequate risk management' => 'The organization does not have a comprehensive risk management plan in place, leaving it vulnerable to potential risks.',
            'Poor record-keeping practices' => 'Record-keeping practices are inadequate, resulting in incomplete or inaccurate documentation.',
            'Supplier and vendor issues' => 'There are ongoing issues with suppliers and vendors, affecting the quality and timeliness of materials and services.',
            'Employee misconduct' => 'Instances of employee misconduct have been reported, including violations of company policies and procedures.',
            'Quality control problems' => 'There are significant quality control issues, leading to defects and non-conformance with specifications.',
            'Environmental hazards' => 'The workplace poses environmental hazards, including improper disposal of waste and emissions that exceed regulatory limits.',
        ];

        if ($column === 'name') {
            return $this->faker->randomElement(array_keys($findings));
        } else {
            return $this->faker->randomElement(array_values($findings));
        }
    }

    private function generateCorrectiveActions()
    {
        $correctiveActions = [
            'Review and update safety protocols',
            'Conduct training sessions for staff',
            'Implement new quality control measures',
            'Schedule regular maintenance checks',
            'Improve documentation and record-keeping practices',
            'Enhance communication channels within the team',
            'Upgrade equipment and tools as necessary',
            'Perform regular audits and inspections',
            'Develop a risk management plan',
            'Increase employee engagement and feedback',
            'Establish a continuous improvement program',
            'Ensure compliance with industry standards and regulations',
            'Optimize workflow and processes',
            'Strengthen supplier and vendor relationships',
            'Implement a robust incident reporting system',
        ];

        return $this->faker->randomElement($correctiveActions);
    }
}