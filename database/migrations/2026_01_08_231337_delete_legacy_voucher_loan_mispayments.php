<?php

    use App\Models\Imprest;
    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Transaction::find(68015)->update(['credit' => 65485.49]);
            Transaction::find(68026)->update(['credit' => 50081.90]);
            Transaction::find(68039)->update(['credit' => 115602.50]);
            Transaction::whereIn('id', [68075, 68078, 68081, 68084, 68087])->delete();
            Transaction::whereIn('id', [68103, 68106, 68109, 68112, 68116])->delete();
            Transaction::whereIn('id', [68144])->delete();
            Transaction::find(67514)->update(['credit' => 192048.98]);
            Transaction::find(71822)->update(['debit' => 20347.04]);
            Transaction::find(71824)->update(['debit' => 22.47]);
            Transaction::find(71916)->update(['debit' => 139434.81]);
            Imprest::find(3089)->update(['amount' => 5256.47]);
            Transaction::find(67304)->update(['credit' => 5256.47]);
            Transaction::find(67399)->update(['credit' => 34385.93]);
            Transaction::whereIn('id', [71929, 71931])->delete();
            Transaction::whereIn('id', [71937, 71939, 71941, 71943, 71945, 71947, 71949, 71951, 71953, 71955, 71957, 71959, 71961, 71963, 71965])->delete();
            Transaction::whereIn('id', [71968, 71970, 71972, 71974, 71976, 71978, 71980, 71982, 71984, 71986, 71988, 71990, 71992, 71994, 71996])->delete();
            Transaction::whereIn('id', [71999, 72001, 72003, 72005, 72007, 72009, 72011, 72013, 72015, 72017, 72019, 72021, 72023, 72025, 72027])->delete();
            Transaction::whereIn('id', [72030, 72032, 72034, 72036, 72038, 72040, 72042, 72044, 72046, 72048, 72050, 72052, 72054, 72056, 72058])->delete();
            Transaction::whereIn('id', [72061, 72063, 72065, 72067, 72069, 72071, 72073, 72075, 72077, 72079, 72081, 72083, 72085, 72087, 72089, 72091, 72093])->delete();
            Transaction::whereIn('id', [72096])->delete();
            Transaction::whereIn('id', [72100])->delete();
            Transaction::whereIn('id', [72118])->delete();
//            Transaction::find(3033)->update(['transaction_date' => '2026-01-07']);
        }
    };
