<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Autodrive\Models\Member;
use Autodrive\Repositories\Membership;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Autodrive\Repositories\Address;
use Autodrive\Models\Province;
use Autodrive\Models\Regency;
use Autodrive\Models\District;

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['json.response']], function () {
    Route::prefix('jr')->group(function () {
        Route::get('psg.json', function () {
            return response(['test']);
        });
    });
});
Route::prefix('test')->group(function () {

    Route::prefix('addr')->group(function () {
        Route::get('/', function (Request $request, Province $province) {
            $province->all()->map(
                function ($province) {
                    echo '<p><a href="' . route('test.regency', [$province->id]) . '">' . $province->name . '</a></p>';
                }
            );
        })->name('test.province');

        Route::get('/{province_id}', function (Request $request, Regency $regency) {
            $regency->where('province_id', $request->province_id)->get()->map(
                function ($regency) use($request) {
                    echo '<p><a href="' . route('test.district', [$request->province_id, $regency->id]) . '">' . $regency->name . '</a></p>';
                }
            );
        })->name('test.regency');

        Route::get('/{province_id}/{regency_id}', function (Request $request, District $district) {
            $district->where('regency_id', $request->regency_id)->get()->map(
                function ($district) {
                    echo '<p><a href="">' . $district->name . '</a></p>';
                }
            );
        })->name('test.district');
    });
});



Route::get('/test/{id}', function (Request $request, Membership $ms, Province $pv) {


    dd($pv->first());
// dd($ms->lev());

// dd(config('level.1.name'));
// dd(Uuid::uuid4());

    $id = $request->id;
    /* $request->validate([
        'id' => 'required'
    ]); */

    // dd($request->input('id'));

    /* $validator = Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|integer|max:999999|min:0'
    ]);

    if ($validator->fails())
    {
        throw new \Error($validator->errors());
        // return response()->json(['error'=>$validator->errors()], 401);
    } */
    // dd(Member::with('grand_parent')->first());

    $member = Member::with('parent:id,parent_id,qualification_id')->get()->whereStrict('id', (int) $id)->first();

    // dd($member->increment_qualification());
    dd([
        $member->member_id,
        $member->siblings,
        $member->parent,
        $member->ancestors,
        $member->descendants,
        $member->children,
    ]);

    /* dd($members->get()->reduce(
        function($carry, $item) {
            $carry[] = $item->get();
            return $carry;
        },
        []
    )); */
    /* $r = $first->get()->reduce(
        function($carry, $item) {
            $carry[] = $item->id;
        },
        []
    );
    dd($r); */

});

Route::get('/ini', function () {
    ini_set('max_execution_time', 90);
    dd(ini_get_all());
});

Route::get('/mm', function (Membership $mm) {
    ini_set('max_execution_time', 360);
    return response()->json($mm->generate_seed(8));
});

Route::get('/now', function () {
    dd(now()->toDateTimeString());
});

Route::get('/member/{id}', function ($id, Request $request, Member $member) {
    /* $member = new Members([
        'id' => 2,
        'parent_id' => 1,
        'name' => 'test2',
        'email' => 'test2@yahoo.com'
    ]);

    // dd($member);

    $member->save(); */
    // dd($id);
    // $member::find(1);
    // dd($member);
    // $members = Member::with('parent:id,parent_id,qualification_id');
    // dd($members->get()->first()->test);
    // dd($first->get()->whereStrict('id', (int) $id)->first()->parent);
        /* DB::beginTransaction();
        try {
        $pm = $member->find(1)->member_id;
        $x = $member->find(2);
        $x->parent_member_id = $pm;
        $x->save();
        DB::commit();
        } catch(\Exception $e) {
            dd($e);
            DB::rollback();
        } */
        $x = $member->find(2)->member_parent;
        dd($x);



        // dd($member->where('id', 2)->with('member_parent', 'village', 'village.district', 'village.district.regency', 'village.district.regency.province')->get()->first());
});

Route::get('/rel', function () {
    // dd(Uuid::uuid4()->getBytes());

    DB::transaction(function () {
        $json = '[{"id":1,"qualification_id":3,"parent_id":null},{"id":2,"qualification_id":2,"parent_id":1},{"id":3,"qualification_id":2,"parent_id":1},{"id":4,"qualification_id":2,"parent_id":1},{"id":5,"qualification_id":2,"parent_id":1},{"id":6,"qualification_id":1,"parent_id":2},{"id":7,"qualification_id":1,"parent_id":2},{"id":8,"qualification_id":1,"parent_id":2},{"id":9,"qualification_id":1,"parent_id":3},{"id":10,"qualification_id":1,"parent_id":3},{"id":11,"qualification_id":1,"parent_id":3},{"id":12,"qualification_id":1,"parent_id":4},{"id":13,"qualification_id":1,"parent_id":4},{"id":14,"qualification_id":1,"parent_id":4},{"id":15,"qualification_id":1,"parent_id":5},{"id":16,"qualification_id":1,"parent_id":5},{"id":17,"qualification_id":1,"parent_id":5}]';
        $json = json_decode($json);
        $json = collect($json);
        $json->each(function($member) {
            $member = ((array) $member);
            $member['name'] = 'name ' . $member['id'];
            $member['village_id'] = '3372010009';
            $memberI = new Member($member);
            $memberI->save();
            // print_r($member);
        });
    });



});

Route::get('/truncate', function () {
    DB::table('members')->truncate();
    DB::table('users')->truncate();
});

Route::post('/authenticate', function (Request $request) {
    // response()->json(['email' => request('email'), 'password' => request('password')]);

    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
        $user = Auth::user();
        // return response()->json($user);
        // $success['access_token'] =  $user->createToken('pac')->accessToken;
        // $success['token_type'] = 'bearer';
        return response()->json($user->createToken('pac')->accessToken, 200);
    }
    else{
        return response()->json(['error'=>'Unauthorised'], 401);
    }
});

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
/* Route::get('/', function () {
    return response('test');
})->name('login'); */

Route::get('/home', 'HomeController@index')->name('home');
