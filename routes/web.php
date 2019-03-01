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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/address/{id}', function (Request $request, Address $address) {
    DB::beginTransaction();
    try {
        $address = $address::all_code_from_village_code($request->id);
        // throw new \Exception('error');
    } catch(\Exception $error) {
        dd($error);
        DB::rollBack();
    }
    DB::commit();
    dd($address);
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

Route::get('/desc/{id}', function ($id) {
    /* $member = new Members([
        'id' => 2,
        'parent_id' => 1,
        'name' => 'test2',
        'email' => 'test2@yahoo.com'
    ]);

    // dd($member);

    $member->save(); */
    // dd($id);

    $members = Member::with('parent:id,parent_id,qualification_id');
    // dd($members->get()->first()->test);
    dd($first->get()->whereStrict('id', (int) $id)->first()->parent);

});

Route::get('/rel', function () {
    // dd(Uuid::uuid4()->getBytes());
    $json = '[{"id":1,"qualification_id":3,"parent_id":null},{"id":2,"qualification_id":2,"parent_id":1},{"id":3,"qualification_id":2,"parent_id":1},{"id":4,"qualification_id":2,"parent_id":1},{"id":5,"qualification_id":2,"parent_id":1},{"id":6,"qualification_id":1,"parent_id":2},{"id":7,"qualification_id":1,"parent_id":2},{"id":8,"qualification_id":1,"parent_id":2},{"id":9,"qualification_id":1,"parent_id":3},{"id":10,"qualification_id":1,"parent_id":3},{"id":11,"qualification_id":1,"parent_id":3},{"id":12,"qualification_id":1,"parent_id":4},{"id":13,"qualification_id":1,"parent_id":4},{"id":14,"qualification_id":1,"parent_id":4},{"id":15,"qualification_id":1,"parent_id":5},{"id":16,"qualification_id":1,"parent_id":5},{"id":17,"qualification_id":1,"parent_id":5}]';

    $json = json_decode($json);

    $json = collect($json);

    $json->each(function($member) {
        $member = ((array) $member);
        $member['name'] = 'name ' . $member['id'];
        // $member['member_id'] = Uuid::uuid4()->getBytes();
        $memberI = new Member($member);
        $memberI->save();
        // print_r($member);
    });


});
