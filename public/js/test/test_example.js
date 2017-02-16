/**
 * Created by Spark on 2017/2/13.
 */
//var target = require('./test_target');
//var assert = require('assert');
//var expect = require('chai').expect;

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
    })

    it('試作測試 a * b function', function () {
        // arrange
        var a = 1;
        var b = 2;

        // act
        var expected = 2;
        var actual = test2(a, b);

        // assert
        expect(actual).to.equal(expected);
    })
})