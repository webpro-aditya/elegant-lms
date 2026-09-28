<div>
    <style>
        /* drawflow */

        .drawflow_content_node .image-preview {
            height: 60px;
            background: #f1f1f1;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #D1D1D1;
        }

        .drawflow_content_node .image-preview img {
            height: 60px;
            width: 100px;
            object-fit: cover;
            min-width: 100px
        }

        .drawflow_content_node .option_title,
        .drawflow_content_node .ans_title{
            flex-grow: 1;
        }

        .drawflow_content_node .primary_label2{
            gap: 10px;
        }

        /* .ansNode .drawflow_content_node .primary_label2{
            flex-direction: row-reverse;
        } */

        .drawflow_content_node .option_title,
        .drawflow_content_node .ans_title{
            height: 60px;
            font-size: 22px;
        }

        .drawflow {
            min-width: 900px;
            overflow: auto;
            max-width: 100%;
            width: 100%;
        }

        .drawflow .drawflow-node{
            width: calc(100% / 12* 5)!important;
        }

        .parent-drawflow{
            overflow: auto!important;
        }
        html[dir='rtl'] .drawflow {
            direction: ltr;
        }

        html[dir='rtl'] .drawflow .connection {
            right: auto;
            left: 0;
        }

    </style>

    <div class="quiz__details">
        <div class="container">
            <div class="row justify-content-center ">
                <div class="col-xl-10">
                    <div class="row">
                        <div class="col-12">

                            <div class="mb_30">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="mb-0">{{__('student.Result Sheet')}}</h3>
                                    <a href="javascript:history.back()" class="theme_btn small_btn2"><i class="ti-arrow-left"></i> {{__('common.Back')}}</a>
                                </div>
                                <!-- quiz_test_header  -->
                                {{--<div class="quiz_test_header">
                                    <h3>{{__('student.Result Sheet')}}</h3>
                                </div>--}}

                                @php
                                    $totalQus = totalQuizQus($quizTest->quiz_id);
                                    $totalAns = count($quizTest->details);
                                    $totalCorrect = 0;
                                    $totalScore = totalQuizMarks($quizTest->quiz_id);
                                    $score = 0;
                                    if ($totalAns != 0) {
                                        foreach ($quizTest->details as $test) {
                                            if ($test->status == 1) {
                                                $score += $test->mark ?? 1;
                                                $totalCorrect++;
                                            }
                                        }
                                    }
                                    $totalWrong = $totalAns - $totalCorrect;
                                    $unattempted = $totalQus - $totalAns;
                                    
                                    $passMark = $quiz->percentage ?? 0;
                                    $mark = $totalScore > 0 ? round($score / $totalScore * 100, 2) : 0;
                                    $status = $mark >= $passMark ? "Passed" : "Failed";
                                @endphp

                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('student.Exam Score') }}</th>
                                                <td>{{ $score }} Out of {{ $totalScore }}</td>
                                                <th>{{ __('frontend.Percentage') }}</th>
                                                <td>{{ $mark }}%</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('quiz.Total Questions') }}</th>
                                                <td>{{ $totalQus }}</td>
                                                <th>{{ __('frontend.Result') }}</th>
                                                <td>
                                                    @if($status == 'Passed')
                                                        <span class="text-success">{{__("frontend.Pass")}}</span>
                                                    @else
                                                        <span class="text-danger">{{__("frontend.Failed")}}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('student.Correct Answer') }}</th>
                                                <td><span class="text-success">{{ $totalCorrect }}</span></td>
                                                <th>{{ __('student.Wrong Answer') }}</th>
                                                <td><span class="text-danger">{{ $totalWrong }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('student.Unattempted') }}</th>
                                                <td colspan="3"><span class="text-warning">{{ $unattempted }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive mb-4" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="sticky-top bg-white">
                                            <tr>
                                                <th width="10%">#</th>
                                                <th width="70%">{{ __('frontend.Question') }}</th>
                                                <th width="20%">{{ __('frontend.Result') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($questions))
                                                @foreach($questions as $index => $question)
                                                    @php
                                                        if ($quiz->show_only_wrong_ans_in_ans_sheet==1 && !$question['isWrong'] && $quiz->total_correct_ans!=count($questions) ){
                                                           continue;
                                                        }
                                                        if(isset($question['isSubmit']) && $question['isSubmit']){
                                                            if(isset($question['isWrong']) &&  $question['isWrong']){
                                                                $qStatus = __('student.Wrong Answer');
                                                                $qClass = 'text-danger';
                                                            } else {
                                                                $qStatus = __('student.Correct Answer');
                                                                $qClass = 'text-success';
                                                            }
                                                        } else {
                                                            $qStatus = __('student.Unattempted');
                                                            $qClass = 'text-warning';
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{!! \Illuminate\Support\Str::limit(strip_tags($question['qus']), 150) !!}</td>
                                                        <td class="{{ $qClass }} fw-bold">{{ $qStatus }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- quiz_test_body  -->
                                <div class="quiz_result_sheet_body">
                                    <div class="result_sheet_view">
                                        @php
                                            $count=1;
                                        @endphp
                                        @if(isset($questions))
                                            @foreach($questions as $question)
                                                @php
                                                    if ($quiz->show_only_wrong_ans_in_ans_sheet==1 && !$question['isWrong'] && $quiz->total_correct_ans!=count($questions) ){
                                                       continue;
                                                    }
                                                @endphp


                                                @php
                                                    if(isset($question['isSubmit'])){
                                                        if(isset($question['isWrong']) &&  $question['isWrong']){
                                                            $isWrongQus= true;
                                                        }else{
                                                            $isWrongQus =false;
                                                        }
                                                    }else{
                                                        $isWrongQus =true;
                                                    }

                                                @endphp
                                                <div
                                                    class="single_result_view  {{$isWrongQus ? 'wrong' : 'correct'}}">
                                                    <h5 class="single_result_count">{{__('frontend.Question')}}
                                                        : {{$count}}</h5>
                                                    <div class="single_result_view_inner">
                                                        <h4 class="single_result_question">
                                                            @if($question['type']=="C")
                                                                {!! getClozeOptions($question['qusBank'],$question['option']) !!}
                                                            @else
                                                                {!! @$question['qus'] !!}
                                                            @endif


                                                            @if(!$question['isSubmit'])

                                                                <small class="text-danger">
                                                                    ({{trans('quiz.Not Submitted')}}
                                                                    )
                                                                </small>

                                                            @endif
                                                        </h4>
                                                        @if(!empty($question['qusBank']->explanation))
                                                            <p class=" mb-4">
                                                                <span>{{__('quiz.Explanation')}}:</span>
                                                                {!! $question['qusBank']->explanation !!}
                                                            </p>
                                                        @endif
                                                        <div class="row mt-4">
                                                            <div class="col-lg-12">

                                                                @if($question['type']=="M")
                                                                    <ul>
                                                                        @if(!empty($question['option']))
                                                                            @foreach($question['option'] as $option)
                                                                                @php
                                                                                    $showRightAns =true;
                                                                                if ($quiz->show_correct_ans_in_ans_sheet!=1){
                                                                                    if(isset($option['submit']) && $option['submit']){
                                                                                        $showRightAns=true;
                                                                                    }else{
                                                                                        $showRightAns=false;
                                                                                    }
                                                                                }

                                                                                @endphp
                                                                                @if($option['right'] && $showRightAns)
                                                                                    <li>
                                                                                        <label
                                                                                            class="primary_checkbox2 d-flex">
                                                                                            <input checked=""
                                                                                                   type="checkbox"
                                                                                                   disabled>
                                                                                            <span
                                                                                                class="checkmark mr_10"></span>
                                                                                            <span
                                                                                                class="label_name ">{{$option['title']}}</span>
                                                                                        </label>
                                                                                    </li>

                                                                                @else

                                                                                    @if(isset($option['wrong']) && $option['wrong'])
                                                                                        <li>
                                                                                            <label
                                                                                                class="primary_checkbox2 error_ans  d-flex">
                                                                                                <input checked=""
                                                                                                       type="checkbox"
                                                                                                       disabled>
                                                                                                <span
                                                                                                    class="checkmark mr_10"></span>
                                                                                                <span
                                                                                                    class="label_name ">{{$option['title']}} </span>
                                                                                            </label>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <label
                                                                                                class="primary_checkbox2 d-flex">
                                                                                                <input type="checkbox"
                                                                                                       disabled>
                                                                                                <span
                                                                                                    class="checkmark mr_10"></span>
                                                                                                <span
                                                                                                    class="label_name ">{{$option['title']}}</span>
                                                                                            </label>
                                                                                        </li>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </ul>
                                                                @elseif($question['type']=="X")
                                                                    @if(isset($question['qusBank']))
                                                                        @php
                                                                            $qusBank=     $question['qusBank']
                                                                        @endphp
                                                                        @include(theme('partials._quiz_matching_type_preview'),compact('qusBank'))
                                                                    @endif
                                                                @elseif($question['type']=="O")
                                                                    <ul>
                                                                        @if(!empty($question['option']))
                                                                            @foreach($question['option'] as $option)
                                                                                @php
                                                                                    $showRightAns =true;
                                                                                if ($quiz->show_correct_ans_in_ans_sheet!=1){
                                                                                    if(isset($option['submit']) && $option['submit']){
                                                                                        $showRightAns=true;
                                                                                    }else{
                                                                                        $showRightAns=false;
                                                                                    }
                                                                                }

                                                                                @endphp
                                                                                @if($option['right'] && $showRightAns)
                                                                                    <li>
                                                                                        <label
                                                                                            class="primary_checkbox2 d-flex">
                                                                                            <input checked=""
                                                                                                   type="checkbox"
                                                                                                   disabled>
                                                                                            <span
                                                                                                class="checkmark d-none mr_10"></span>
                                                                                            <span
                                                                                                class="label_name ">{{$option['title']}}</span>
                                                                                        </label>
                                                                                    </li>

                                                                                @else

                                                                                    @if(isset($option['wrong']) && $option['wrong'])
                                                                                        <li>
                                                                                            <label
                                                                                                class="primary_checkbox2 error_ans  d-flex">
                                                                                                <input checked=""
                                                                                                       type="checkbox"
                                                                                                       disabled>
                                                                                                <span
                                                                                                    class="checkmark  d-none mr_10"></span>
                                                                                                <span
                                                                                                    class="label_name ">{{$option['title']}} </span>
                                                                                            </label>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <label
                                                                                                class="primary_checkbox2 d-flex">
                                                                                                <input type="checkbox"
                                                                                                       disabled>
                                                                                                <span
                                                                                                    class="checkmark  d-none mr_10"></span>
                                                                                                <span
                                                                                                    class="label_name ">{{$option['title']}}</span>
                                                                                            </label>
                                                                                        </li>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </ul>
                                                                @elseif($question['type']=="P")

                                                                    <ul>
                                                                        @if(!empty($question['option']))
                                                                            @foreach($question['option'] as $option)
                                                                                <li>
                                                                                    <label
                                                                                        class="primary_checkbox2 d-flex">

                                                                                        <span
                                                                                            class="label_name ">{{$option['title']}}</span>
                                                                                    </label>
                                                                                    <ul>
                                                                                        @php
                                                                                            $qusBank=     $question['qusBank'];
                                                                                             $matching_assigns =$question['matching']??[];
                                                                                        @endphp
                                                                                        @foreach($question['answer'] as $answer)

                                                                                            @php
                                                                                                $has_answer = $matching_assigns
                                                                                                ->where('question_id',$qusBank->id)
                                                                                                ->where('option_id',$option['id']??'')
                                                                                                ->where('answer_id',$answer['id']??'')
                                                                                                ->count();
                                                                                            @endphp
                                                                                            @if($has_answer>0)
                                                                                                <li class="text-{{$answer['status']==1?'success':'danger'}}">{{$answer['title']}}</li>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </ul>
                                                                                    {{--                                                                                    @endif--}}
                                                                                </li>
                                                                            @endforeach
                                                                        @endif
                                                                    </ul>

                                                                @elseif($question['type']=="S" || $question['type']=="L")
                                                                    {!! $question['answer']??"" !!}
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
