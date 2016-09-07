title: JavaScript函数式编程初窥
date: 2016-09-01 04:20
cover_index: /img/FunctionalProgramingOfJavaScript-cover_detail.png
cover_detail: /img/FunctionalProgramingOfJavaScript-cover_detail.png
tags: functional programing javascript
comments: trues
---

初次想去好好学一下函数式编程是有一次回西安，正好去听了[欧阳继超](https://github.com/jcouyang)的分享，被 monad 搞得云里雾里的，于是回来后准备好好的学习一下。

# 函数是一等公民

* 函数可作为参数
* 函数可作为返回值

# 柯里化（curry）

我们把一个多参的函数变成一次只能接受一个参数的函数的过程叫做柯里化。只传递给函数一部分参数来调用它，让它返回一个函数去处理剩下的参数。

```
const add = x => y => x + y
```

用es5的写法如下

```
var add = function (x) {
    return function (y) {
        return x + y;
    };
};
var increment = add(1);
var addTen = add(10);
increment(2)            //3
addTen(2)               //12
```

可以用 [Rmada.js](http://ramdajs.com/0.22.1/index.html) 或者 [loadash-fp](https://github.com/lodash-archive/lodash-fp) 来处理柯里化 

# thunk（槽）

thunk（槽）是指有一些操作不被立即执行，也就是说准备好一个函数，但是不执行，默默等待着合适的时候被合适的人调用。（惰性求值）

```
function f (m) {
    return m * 2;
}
f(x + 5);   
// 传值调用

var thunk = function (x) {
    return x + 5;
};
function f (x) {
    return thunk(x) * 2;
}
f(x);          
//传名调用
```

常用 [thunkify](https://github.com/tj/node-thunkify) 来直接生成 thunk 函数

```
// thunk函数
function readFile (path, encoding) {
    return function (cb) {
        fs.readFile(path, encoding, cb);
    };
}
```

```
// thunkify
var readFile = thunkify(fs.readFile);
```

例如使用 co 来同步调用 readFile：

```
var co = require('co'), fs = require('fs'), Promise = require('es6-promise').Promise;
function readFile (path, encoding) {
    return function (cb) {
        fs.readFile(path, encoding, cb);
    };
}
co(function* () {
    var a = yield readFile('a.txt', {encoding: 'utf8'});
    console.log(a); // a
    var b = yield readFile('b.txt', {encoding: 'utf8'});
    console.log(b); // b
    var c = yield readFile('c.txt', {encoding: 'utf8'});
    console.log(c); // c
    return yield Promise.resolve(a + b + c);
}).then(function (val) {
    console.log(val); // abc
}).catch(function (error) {
    console.log(error);
});
```

# 函数组合（compose）

f 和 g 都是函数，x 是在它们之间通过“管道”传输的值。

```
var compose = function (f, g) {
    return function (x) {
        return f(g(x));
    };
};
var toUpperCase = function (x) {
    return x.toUpperCase();
};
var exclaim = function (x) {
    return x + '!';
};
var shout = compose(exclaim, toUpperCase);
shout("send in the clowns");
// "SEND IN THE CLOWNS!"
```