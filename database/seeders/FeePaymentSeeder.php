<?php

namespace Database\Seeders;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use Illuminate\Database\Seeder;

class FeePaymentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::whereNotNull('roll_number')->get()->keyBy('roll_number');
        $feeStructures = FeeStructure::all()->keyBy('title');

        $tuit24  = $feeStructures['BS CS Tuition Fee — Per Semester 2024-25']?->id;
        $exam24  = $feeStructures['BS CS Examination Fee — Per Semester 2024-25']?->id;
        $lab24   = $feeStructures['BS CS Lab Fee — Per Semester 2024-25']?->id;
        $admCS24 = $feeStructures['BS CS Admission Fee (One Time) 2024-25']?->id;
        $tuitBed = $feeStructures['B.Ed Tuition Fee — Per Semester 2024-25']?->id;
        $admBed  = $feeStructures['B.Ed Admission Fee (One Time) 2024-25']?->id;
        $lib24   = $feeStructures['Library Fee — Annual 2024-25']?->id;
        $tuit23  = $feeStructures['BS CS Tuition Fee — Per Semester 2023-24']?->id;
        $exam23  = $feeStructures['BS CS Examination Fee — Per Semester 2023-24']?->id;

        $cash   = PaymentMethodEnum::Cash->value;
        $bank   = PaymentMethodEnum::BankDraft->value;
        $online = PaymentMethodEnum::OnlineTransfer->value;
        $jazz   = PaymentMethodEnum::JazzCash->value;
        $easy   = PaymentMethodEnum::EasyPaisa->value;

        $paid    = PaymentStatusEnum::Paid->value;
        $pending = PaymentStatusEnum::Pending->value;
        $overdue = PaymentStatusEnum::Overdue->value;
        $partial = PaymentStatusEnum::Partial->value;
        $waived  = PaymentStatusEnum::Waived->value;

        $payments = [
            // ─── BS CS Batch 2024 — Semester 1 Fees ───────────────────────────────
            ['challan' => 'CHN-CS24-001', 'roll' => 'CS-2024-0001', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' => 25000, 'status' => $paid,    'method' => $bank,   'due_date' => '2024-10-01', 'paid_date' => '2024-09-28', 'bank' => 'HBL'],
            ['challan' => 'CHN-CS24-002', 'roll' => 'CS-2024-0001', 'type' => FeeTypeEnum::Exam->value,      'fs' => $exam24,  'sem' => 1, 'due' =>  3000, 'paid_amt' =>  3000, 'status' => $paid,    'method' => $cash,   'due_date' => '2024-10-15', 'paid_date' => '2024-10-10'],
            ['challan' => 'CHN-CS24-003', 'roll' => 'CS-2024-0001', 'type' => FeeTypeEnum::Admission->value, 'fs' => $admCS24, 'sem' => 1, 'due' => 15000, 'paid_amt' => 15000, 'status' => $paid,    'method' => $bank,   'due_date' => '2024-09-15', 'paid_date' => '2024-09-14', 'bank' => 'MCB'],
            ['challan' => 'CHN-CS24-004', 'roll' => 'CS-2024-0002', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' =>     0, 'status' => $waived,  'method' => null,    'due_date' => '2024-10-01', 'paid_date' => null,          'remarks' => 'Merit scholarship — full waiver'],
            ['challan' => 'CHN-CS24-005', 'roll' => 'CS-2024-0002', 'type' => FeeTypeEnum::Exam->value,      'fs' => $exam24,  'sem' => 1, 'due' =>  3000, 'paid_amt' =>  3000, 'status' => $paid,    'method' => $online, 'due_date' => '2024-10-15', 'paid_date' => '2024-10-12', 'bank' => 'UBL'],
            ['challan' => 'CHN-CS24-006', 'roll' => 'CS-2024-0003', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' => 25000, 'status' => $paid,    'method' => $jazz,   'due_date' => '2024-10-01', 'paid_date' => '2024-10-05'],
            ['challan' => 'CHN-CS24-007', 'roll' => 'CS-2024-0003', 'type' => FeeTypeEnum::Exam->value,      'fs' => $exam24,  'sem' => 1, 'due' =>  3000, 'paid_amt' =>  1500, 'status' => $partial, 'method' => $easy,   'due_date' => '2024-10-15', 'paid_date' => '2024-10-20', 'fine' => 100],
            ['challan' => 'CHN-CS24-008', 'roll' => 'CS-2024-0004', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' =>     0, 'status' => $overdue, 'method' => null,    'due_date' => '2024-10-01', 'paid_date' => null,          'fine' => 1500],
            ['challan' => 'CHN-CS24-009', 'roll' => 'CS-2024-0005', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' => 25000, 'status' => $paid,    'method' => $bank,   'due_date' => '2024-10-01', 'paid_date' => '2024-09-30', 'bank' => 'Meezan Bank'],
            ['challan' => 'CHN-CS24-010', 'roll' => 'CS-2024-0006', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' =>     0, 'status' => $waived,  'method' => null,    'due_date' => '2024-10-01', 'paid_date' => null,          'remarks' => 'HEC need-based scholarship'],
            ['challan' => 'CHN-CS24-011', 'roll' => 'CS-2024-0007', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' => 25000, 'status' => $paid,    'method' => $cash,   'due_date' => '2024-10-01', 'paid_date' => '2024-10-08'],
            ['challan' => 'CHN-CS24-012', 'roll' => 'CS-2024-0008', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit24,  'sem' => 1, 'due' => 25000, 'paid_amt' => 25000, 'status' => $paid,    'method' => $online, 'due_date' => '2024-10-01', 'paid_date' => '2024-09-25', 'bank' => 'Allied Bank'],

            // ─── BS CS Batch 2023 — Semester 3 Fees ───────────────────────────────
            ['challan' => 'CHN-CS23-001', 'roll' => 'CS-2023-0001', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit23,  'sem' => 3, 'due' => 23000, 'paid_amt' => 23000, 'status' => $paid,    'method' => $bank,   'due_date' => '2024-02-15', 'paid_date' => '2024-02-10', 'bank' => 'HBL'],
            ['challan' => 'CHN-CS23-002', 'roll' => 'CS-2023-0002', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit23,  'sem' => 3, 'due' => 23000, 'paid_amt' => 23000, 'status' => $paid,    'method' => $online, 'due_date' => '2024-02-15', 'paid_date' => '2024-02-14', 'bank' => 'UBL'],
            ['challan' => 'CHN-CS23-003', 'roll' => 'CS-2023-0004', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit23,  'sem' => 3, 'due' => 23000, 'paid_amt' => 23000, 'status' => $paid,    'method' => $jazz,   'due_date' => '2024-02-15', 'paid_date' => '2024-02-20', 'fine' => 50],
            ['challan' => 'CHN-CS23-004', 'roll' => 'CS-2023-0005', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit23,  'sem' => 3, 'due' => 23000, 'paid_amt' =>     0, 'status' => $waived,  'method' => null,    'due_date' => '2024-02-15', 'paid_date' => null,          'remarks' => 'Merit scholarship'],
            ['challan' => 'CHN-CS23-005', 'roll' => 'CS-2023-0006', 'type' => FeeTypeEnum::Tuition->value,   'fs' => $tuit23,  'sem' => 3, 'due' => 23000, 'paid_amt' => 23000, 'status' => $paid,    'method' => $cash,   'due_date' => '2024-02-15', 'paid_date' => '2024-02-28'],
            ['challan' => 'CHN-CS23-006', 'roll' => 'CS-2023-0001', 'type' => FeeTypeEnum::Exam->value,      'fs' => $exam23,  'sem' => 3, 'due' =>  2800, 'paid_amt' =>  2800, 'status' => $paid,    'method' => $cash,   'due_date' => '2024-03-01', 'paid_date' => '2024-02-28'],

            // ─── B.Ed Batch 2024 — Semester 1 Fees ────────────────────────────────
            ['challan' => 'CHN-EDU-001',  'roll' => 'EDU-2024-0001', 'type' => FeeTypeEnum::Tuition->value,  'fs' => $tuitBed, 'sem' => 1, 'due' => 20000, 'paid_amt' => 20000, 'status' => $paid,    'method' => $bank,   'due_date' => '2024-10-01', 'paid_date' => '2024-09-29', 'bank' => 'HBL'],
            ['challan' => 'CHN-EDU-002',  'roll' => 'EDU-2024-0001', 'type' => FeeTypeEnum::Admission->value,'fs' => $admBed,  'sem' => 1, 'due' => 10000, 'paid_amt' => 10000, 'status' => $paid,    'method' => $cash,   'due_date' => '2024-09-15', 'paid_date' => '2024-09-12'],
            ['challan' => 'CHN-EDU-003',  'roll' => 'EDU-2024-0002', 'type' => FeeTypeEnum::Tuition->value,  'fs' => $tuitBed, 'sem' => 1, 'due' => 20000, 'paid_amt' => 20000, 'status' => $paid,    'method' => $easy,   'due_date' => '2024-10-01', 'paid_date' => '2024-10-03', 'fine' => 50],
            ['challan' => 'CHN-EDU-004',  'roll' => 'EDU-2024-0003', 'type' => FeeTypeEnum::Tuition->value,  'fs' => $tuitBed, 'sem' => 1, 'due' => 20000, 'paid_amt' =>     0, 'status' => $pending, 'method' => null,    'due_date' => '2024-10-01', 'paid_date' => null],
            ['challan' => 'CHN-EDU-005',  'roll' => 'EDU-2024-0004', 'type' => FeeTypeEnum::Tuition->value,  'fs' => $tuitBed, 'sem' => 1, 'due' => 20000, 'paid_amt' => 20000, 'status' => $paid,    'method' => $jazz,   'due_date' => '2024-10-01', 'paid_date' => '2024-10-15', 'fine' => 200],
            ['challan' => 'CHN-EDU-006',  'roll' => 'EDU-2024-0005', 'type' => FeeTypeEnum::Tuition->value,  'fs' => $tuitBed, 'sem' => 1, 'due' => 20000, 'paid_amt' => 20000, 'status' => $paid,    'method' => $online, 'due_date' => '2024-10-01', 'paid_date' => '2024-09-27', 'bank' => 'MCB'],
            ['challan' => 'CHN-EDU-007',  'roll' => 'EDU-2024-0006', 'type' => FeeTypeEnum::Tuition->value,  'fs' => $tuitBed, 'sem' => 1, 'due' => 20000, 'paid_amt' =>     0, 'status' => $overdue, 'method' => null,    'due_date' => '2024-10-01', 'paid_date' => null,          'fine' => 1000],

            // ─── Library Fee ───────────────────────────────────────────────────────
            ['challan' => 'CHN-LIB-001',  'roll' => 'CS-2024-0001',  'type' => FeeTypeEnum::Library->value,  'fs' => $lib24,   'sem' => null, 'due' => 1000, 'paid_amt' => 1000, 'status' => $paid,   'method' => $cash,   'due_date' => '2024-10-31', 'paid_date' => '2024-10-15'],
            ['challan' => 'CHN-LIB-002',  'roll' => 'CS-2024-0002',  'type' => FeeTypeEnum::Library->value,  'fs' => $lib24,   'sem' => null, 'due' => 1000, 'paid_amt' => 1000, 'status' => $paid,   'method' => $cash,   'due_date' => '2024-10-31', 'paid_date' => '2024-10-20'],
            ['challan' => 'CHN-LIB-003',  'roll' => 'CS-2023-0001',  'type' => FeeTypeEnum::Library->value,  'fs' => $lib24,   'sem' => null, 'due' => 1000, 'paid_amt' =>    0, 'status' => $pending,'method' => null,    'due_date' => '2024-10-31', 'paid_date' => null],
        ];

        foreach ($payments as $p) {
            $studentId = $students[$p['roll']]?->id;
            if (! $studentId) {
                continue;
            }

            FeePayment::firstOrCreate(
                ['challan_number' => $p['challan']],
                [
                    'student_id'      => $studentId,
                    'fee_structure_id'=> $p['fs'],
                    'challan_number'  => $p['challan'],
                    'fee_type'        => $p['type'],
                    'semester_number' => $p['sem'],
                    'amount_due'      => $p['due'],
                    'amount_paid'     => $p['paid_amt'],
                    'fine_amount'     => $p['fine'] ?? 0,
                    'discount_amount' => 0,
                    'payment_status'  => $p['status'],
                    'payment_method'  => $p['method'],
                    'due_date'        => $p['due_date'],
                    'payment_date'    => $p['paid_date'],
                    'bank_name'       => $p['bank'] ?? null,
                    'remarks'         => $p['remarks'] ?? null,
                ]
            );
        }
    }
}
