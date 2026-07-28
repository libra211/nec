<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionerSeeder extends Seeder
{
    public function run(): void
    {
        $commissioners = [
            [
                'name'      => 'Hon. Prof. Abednego A. A. Akok',
                'position'  => 'Chairperson',
                'bio'       => 'Appointed as Chairperson of NEC with extensive experience in public administration and electoral management.',
                'photo'     => 'assets/images/chairperson.webp',
                'order_num' => 1,
                'status'    => 'active',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Hon. Sarah N. Wani',
                'position'  => 'Deputy Chairperson',
                'bio'       => 'Experienced legal professional and advocate for democratic governance and electoral integrity.',
                'photo'     => null,
                'order_num' => 2,
                'status'    => 'active',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Hon. James L. Maker',
                'position'  => 'Commissioner',
                'bio'       => 'Specialist in electoral operations and field coordination with decades of public service.',
                'photo'     => null,
                'order_num' => 3,
                'status'    => 'active',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Hon. Dr. Mary A. Nyandeng',
                'position'  => 'Commissioner',
                'bio'       => 'Academic and civil society leader with expertise in civic education and voter outreach.',
                'photo'     => null,
                'order_num' => 4,
                'status'    => 'active',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Hon. John M. Deng',
                'position'  => 'Commissioner',
                'bio'       => 'Former diplomat with extensive experience in international relations and election observation.',
                'photo'     => null,
                'order_num' => 5,
                'status'    => 'active',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Hon. Rebecca N. Kiden',
                'position'  => 'Commissioner',
                'bio'       => 'Gender equality advocate with a strong background in human rights and legal affairs.',
                'photo'     => null,
                'order_num' => 6,
                'status'    => 'active',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ];

        DB::table('nec_commissioners')->insert($commissioners);
    }
}
