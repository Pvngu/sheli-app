<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accident>
 */
class AccidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'injured_person' => $this->faker->numberBetween(2, 101),
            'reporting_user' => $this->faker->numberBetween(1, 1),
            'area_id' => $this->faker->numberBetween(1, 6),
            'days_absent' => $this->faker->numberBetween(1, 10),
            'description' => $this->generateAccidentDescription(),
            'status' => $this->faker->randomElement(['reported', 'in_progress', 'resolved']),
            'date_of_accident' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Generate a more realistic accident description.
     *
     * @return string
     */
    private function generateAccidentDescription(): string
    {
        $scenarios = [
            'While operating machinery, the injured person slipped and fell, resulting in a sprained ankle.',
            'The injured person was struck by a falling object while working on a construction site, causing a minor head injury.',
            'During routine maintenance, the injured person was exposed to hazardous chemicals, leading to skin irritation.',
            'The injured person tripped over an uneven surface and fractured their wrist.',
            'While lifting heavy equipment, the injured person experienced severe back pain.',
            'The injured person was involved in a vehicle collision while on duty, resulting in multiple bruises.',
            'During a fire drill, the injured person suffered from smoke inhalation.',
            'The injured person was bitten by an animal while performing fieldwork.',
            'While climbing a ladder, the injured person lost balance and fell, causing a broken leg.',
            'The injured person was electrocuted while handling electrical wiring, leading to burns on their hands.',
            'The injured person slipped on a wet floor and dislocated their shoulder.',
            'While unloading cargo, the injured person was crushed by a falling crate, resulting in a broken rib.',
            'The injured person was caught in a machine, leading to a severe hand injury.',
            'During a training exercise, the injured person fell and suffered a concussion.',
            'The injured person was exposed to loud noises for an extended period, causing hearing loss.',
            'While working in extreme heat, the injured person experienced heat exhaustion.',
            'The injured person was involved in a forklift accident, resulting in a leg injury.',
            'During a team-building activity, the injured person fell and sprained their knee.',
            'The injured person was struck by a moving vehicle in the company parking lot, causing a broken arm.',
            'While handling sharp tools, the injured person accidentally cut themselves, leading to a deep laceration.',
            'The injured person was hit by a falling object while working at a construction site, resulting in a concussion.',
            'The injured person slipped on ice in the parking lot, resulting in a sprained ankle.',
            'While using a power tool, the injured person suffered a deep cut on their hand.',
            'The injured person was exposed to toxic fumes, leading to respiratory issues.',
            'During a team meeting, the injured person fainted due to low blood sugar.',
            'The injured person was stung by a bee while working outdoors, causing an allergic reaction.',
            'While carrying heavy boxes, the injured person strained their back.',
            'The injured person was involved in a slip and fall accident in the restroom, resulting in a broken wrist.',
            'During a company event, the injured person tripped over a cable and sprained their ankle.',
            'The injured person was burned by hot liquid while working in the kitchen.',
            'While assembling furniture, the injured person accidentally hammered their thumb.',
            'The injured person was involved in a bicycle accident while on a work-related errand, resulting in a fractured collarbone.',
            'During a power outage, the injured person tripped in the dark and injured their knee.',
            'The injured person was bitten by a dog while delivering a package.',
            'While cleaning windows, the injured person fell off a ladder and broke their arm.',
            'The injured person was exposed to extreme cold, leading to frostbite on their fingers.',
            'During a warehouse inspection, the injured person was hit by a falling pallet, causing a head injury.',
            'The injured person was involved in a scuffle with a coworker, resulting in a black eye.',
            'While working in a confined space, the injured person experienced claustrophobia and panic attack.',
            'The injured person was struck by a forklift while walking through the warehouse, resulting in a leg injury.',
            'During a company sports event, the injured person twisted their ankle while playing soccer.'
        ];

        return $this->faker->randomElement($scenarios);
    }
}
