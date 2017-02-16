@extends('layouts.test_layout')
@section('title', 'test')
@section('content')
    <!-- load code you want to test here -->
    <script src="{{ url('/js/test/test_target.js?v=2') }}"></script>
    <!-- load your test files here -->
    <!--<script src="{{ url('/js/test/test_example.js?v=2') }}"></script>-->
    <script>
        describe('試用測試專案', function () {
            it('試作測試 a + b function', function () {
                // arrange
                var a = 1;
                var b = 2;

                // act
                var expected = 3;
                var actual = test1(a, b);

                // assert
                expect(actual).to.equal(expected);
            });

            it('試作測試 a * b function', function () {
                // arrange
                var a = 1;
                var b = 2;

                // act
                var expected = 2;
                var actual = test2(a, b);

                // assert
                expect(actual).to.equal(expected);
            });
        });
    </script>
@stop