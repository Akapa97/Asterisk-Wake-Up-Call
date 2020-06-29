<?php

namespace App\Http\Controllers;

use App\Cdr;
use App\WakeUpCall;
use Illuminate\Http\Request;
use DB;
use Cache;
use Illuminate\Validation\Rule;
use JsValidator;
use Validator;

class AsteriskController extends Controller
{
    /**
     * Define your validation rules in a property in
     * the controller to reuse the rules.
     */
    private $validateFilter;
    private $validateWakeUpCall;

    private $data;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $result = DB::select("SELECT MIN(EXTRACT(YEAR FROM calldate)) AS min_year FROM cdrs");

        $int = [
            [2001, 2007],
            [2101, 2117],
            [2201, 2221],
            [2301, 2312],
            [2050, 2061]
        ];

        $this->data['ext'] = Cache::remember('ext', 120, function () use($int) {
            $ext = [];

            foreach($int as $i) {
                $ext = array_merge($ext, range($i[0], $i[1]));
            }

            return $ext;
        });

        array_unshift($this->data['ext'], __('app.wakeup'));

        array_unshift($this->data['ext'], __('app.all'));

        $this->data['min_year'] = $result[0]->min_year ?? date('Y');

        $this->validateFilter = [
            'disposition' => ['required', 'numeric'],
            'month' => ['required', 'numeric', 'between:0,12'],
            'year' => ['required', 'numeric', 'between:0' . ',' . date('Y')],
        ];

        $this->data['time'] = [
            15,
            30,
            60,
            120
        ];

        $this->validateWakeUpCall = [
            'ext' => [
                'required',
                 Rule::in(array_keys($this->data['ext']))
            ],
            'tries' => [
                'required', 'numeric', 'between:0,10'
            ],
            'waittime' => [
                'required',
                 Rule::in($this->data['time'])
            ],
            'retrytime' => [
                'required',
                 Rule::in($this->data['time'])
            ],
            'datetime' => [
                'required', 'date_format:Y-m-d H:i'
            ],
            'supervisor' => [
                'required',
                 Rule::in(array_keys($this->data['ext']))
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validate_data = $request->validate($this->validateFilter);

        return view('cdrs', $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxIndex(Request $request)
    {
        if($request->ajax()) {
            $sql = "SELECT src, dst, disposition, calldate, duration FROM cdrs WHERE id != :id";

            $data['id'] = 0;

            if ($request->year) {
                $data['year'] = $request->year;

                $sql .= " AND EXTRACT(YEAR FROM calldate) = :year";
            }

            if ($request->month) {
                $data['month'] = $request->month;

                $sql .= " AND EXTRACT(MONTH FROM calldate) = :month";
            }

            if ($request->disposition) {
                $data['disposition'] = __('app.dispositions')[$request->disposition];

                $sql .= " AND disposition = :disposition";
            }

            if ($request->src) {
                $data['src'] = $this->data['ext'][$request->src];

                $sql .= " AND src = UPPER(:src)";
            }

            $result = DB::select($sql, $data);

            return datatables()->of($result)->toJson();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxWakeUpCall(Request $request)
    {
        if($request->ajax()) {
            $timestamp = strtotime(date('Y-m-d H:i:00'));

            $sql = "SELECT * FROM wake_up_calls WHERE datetime >= ?";

            $result = DB::select($sql, [$timestamp]);

            return datatables()->of($result)->toJson();
        }
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function wakeUpCall(Request $request)
    {
        $this->data['validatorWakeUpCall'] = JsValidator::make($this->validateWakeUpCall);

        return view('wake_up_call', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function wakeUpCallStore(Request $request)
    {
        $validate_data = $request->validate($this->validateWakeUpCall);

        $values = $request->only(['ext', 'datetime', 'tries', 'waittime', 'retrytime', 'supervisor']);

        $values['ext'] = $this->data['ext'][$request->ext];

        $values['supervisor'] = $this->data['ext'][$request->supervisor];

        $values['datetime'] = strtotime($values['datetime']);

        $wakeUpCall = new WakeUpCall;

        $wakeUpCall->fill($values);

        $wakeUpCall->save();

        return redirect()->back()->withSuccess(__('app.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function WakeupCalldestroy($id)
    {
        Validator::make(['id' => $id], [
            'id' => 'required|exists:wake_up_calls,id'
        ])->validate();

        WakeUpCall::destroy($id);

        return response()->json([]);
    }
}
