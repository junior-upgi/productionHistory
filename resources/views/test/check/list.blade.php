@extends('layouts.test_layout')
@section('titel', '')
@section('content')
    <div id="check"></div>
    <script src="{{ url('/js/check/list.js?v=1') }}"></script>
    <script>
        describe('test_getCheckList', function () {
            it('ajax success', function (done) {
                // arrange
                var url = getCheckListUrl;
                var data = $.mockJSON.generateFromTemplate({
                    "user|3-6": [{
                        "id|+1": 1,
                        "name": "@MALE_FIRST_NAME",
                        "birthday": "@DATE_YYYY/@DATE_MM/@DATE_DD"
                    }]
                })
                //check.checkList = data;

                // act
                $.mockjax({
                    url: url,
                    status: 200,
                    responseTime: 750,
                    responseText: data
                });
                getCheckList();
                //done()

                // assert
                expect(check.checkList).to.equal(data);

            });
        });
    </script>
@stop