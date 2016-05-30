title: 基于Koa和co使用node编写cgi
date: 2015-09-10 20:50
cover_index: /img/KoaForCgi-cover_index.png
cover_detail: /img/KoaForCgi-cover_detail.png
tags: koa
comments: true
---

最近有需求需要用到node来写一些简易的cgi，帮助后台同学减轻压力。

用node写cgi有两个问题，一个是端口，我们不能每加一个cgi就新增一个端口；第二个是异步回调问题，实现中会遇到很多例如读DB、读文件的异步行为，多层的callback会让代码很繁琐且不易输出内容。

于是想着用最近很火的tj大神的Koa来搭建一套node-cgi框架。

先说说koa，koa是基于co的类似于express的web开发框架，了解koa首先要了解co，了解co首先要了解ES6的generator。

#1.generator

generator是ES6的新函数，写法如下：

```
function* generator(){
   yield hello;
   yield word;
}

var G = generator();

console.log(G.next()); //{ value: hello, done: false }
console.log(G.next()); //{ value: word, done: false }
console.log(G.next()); //{ value: undefined, done: true }
```

generator使用yield的方法，让函数内部执行暂定，只有当调用next()方法的时候，才会接着往下走，同时返回一个对象，返回对象有两个值，一个是yield后面执行的结果，一个是done字段，如果yield都执行完了 done 字段就变为true。

使用yield来阻断函数内部执行，就可以将我们的异步函数转成同步执行。



#2. co

下来再看co，co帮助我们实现了自动调用next()方法，在co内部写yield，等待yield执行完毕就可以继续向下进行。

例如使用tof获取用户信息：

```
co(function *() {
    var user = yield checkLogin(ticket, ip);
    console.log(user);
})

function checkLogin(ticket, ip){
    return function (done) {
        tof.checkLogin(ticket, ip, function(err, result){
            var user = result;
            done(null, user);
        });
    };
}
```

#3. koa

koa和express一样，使用中间件：

```
var koa = require('koa');

var app = koa();
app.use(function *() {
  this.body = 'Hello World!';
});

app.listen(8888);
```

 koa的Context 把 node 的 request, response 对象封装进一个单独对象, 并提供许多开发 web 应用和 APIs 有用的方法. 那些在 HTTP server 开发中使用非常频繁操作, 直接在 Koa 里实现, 而不是放在更高层次的框架, 这样中间件就不需要重复实现这些通用的功能。

```
app.use(function *(){
  this; // is the Context
  this.request; // is a koa Request
  this.response; // is a koa Response
});
```

 需要输出的内容，直接赋值给this.body即可。



4. node-cgi

了解完co和koa，我们就可以搭建一个简易的node-cgi框架，目录如下：



app.js作为入口，使用

node --harmony-generators app.js 
来进行启动，在modules这块使用按需加载，拿到url里的参数后再加载对应的module：

```
var cgi = require('./modules/' + cgiName);

var result = yield function(done){
    cgi('get', param, done);
}
```

这样每次我们发布 只需替换modules文件夹里的js文件即可，不需要启停node服务（和php很像）。

而modules里的cgi文件，可以使用co来处理内部的异步回调，方法和koa相同，愉快同步向下写js从此开始了~

下面附上代码

app.js

```
/**
 * @fileOverview node-cgi入口
 * @version 1.0.0
 * @author <a href="mailto:lucienxu@tencent.com">lucienxu</a>
 * @date 2015/9/8
 * @copyright Copyright (c) 2014, Tencent Inc. All rights reserved.
 * @see [link]
 */

var logger = require('koa-logger');
var router = require('koa-router')();
var parse = require('co-body');
var tof = require('tof')('b90b498f8ab441afbcf4f023f7dd8e08');
var koa = require('koa');
var app = koa();


var posts = [];

var user;

app.keys = ['some secret hurr'];

//app.env = 'production';

//console.log(app.env);

// middleware

app.use(logger());

router.get('/node-cgi/:cgiName', get);
router.post('/node-cgi/:cgiName', post);


app.use(router.routes()).use(router.allowedMethods());


app.use(function *() {
    console.log('tof');
    console.log(user)
    if(user){
        return;
    }
    if ('production' === app.env) {
        console.log('productation')

        var ticket = this.cookies.get('TCOA_TICKET');
        var ip = this.ip.substr(7);
        var value;
        yield checkLogin(ticket, ip);

        console.log(user);


    } else {

        console.log('dev');
        user = {
            loginname: "lucienxu",
            chinesename: "徐扬",
            deptname: "微信广告"
        };

    }



});

function checkLogin(ticket, ip){


    return function (done) {
        tof.checkLogin(ticket, ip, function(err, result){
            user = result;
            //user = 1;
            done();
        });
    };

}

// route definitions

function *get(next){
    yield next;

    var cgiName = this.params.cgiName;
    console.log(cgiName + ': begin--get');

    var param = this.request.query;

    this.type = 'text/html; charset=utf-8';
    this.set('Cache-Control', 'no-cache');
    this.set('Connection', 'keep-alive');

    var cgi = require('./modules/' + cgiName);

    var result = yield function(done){
        cgi('get', param, done);
    }

    this.body = param.callback + '(' + JSON.stringify(result) + ')';

    console.log(cgiName + ': done--get');
    return;
}

function *post(next){
    yield next;

    var cgiName = this.params.cgiName;
    console.log(cgiName + ': begin--post');
    var param = yield parse.form(this);

    this.type = 'application/json; charset=utf-8';
    this.set('Cache-Control', 'no-cache');
    this.set('Connection', 'keep-alive');
    this.set("Access-Control-Allow-Origin", this.header.origin);
    this.set("Access-Control-Allow-Credentials", "true");

    var cgi = require('./modules/' + cgiName);

    var result = yield function(done){
        cgi('post', param, done);
    }

    this.body = result;

    console.log(cgiName + ': done--post');
    return;
}




// listen

app.listen(33010);
console.log('listening on port 33010');
```


sns_super_white_list.js
```
/**
 * @fileOverview 超级白名单cgi
 * @version 1.0.0
 * @author <a href="mailto:lucienxu@tencent.com">lucienxu</a>
 * @date 2015/9/8
 * @copyright Copyright (c) 2014, Tencent Inc. All rights reserved.
 * @see [link]
 */

var Sequelize = require('sequelize');
var co = require('co');
var db = new Sequelize('mysql://root@127.0.0.1:3306/xxx');

module.exports = function (method, param, resp) {
    /*
    *   三个参数，method是get/post，param是传入参数，resp是输出数据
    *   resp(err, result);
    *   resp传入两个参数，第一个是错误信息，将通过console打印出来，第二项是输出的结果result，result为json格式
    */

    co(function *() {
        //db.sync();

        db["t_misc_conf"] = db.define('t_misc_conf', {
            key: {
                type: Sequelize.STRING,
                primaryKey: true,
            },
            value: Sequelize.TEXT,
        }, {
            timestamps: false,
            freezeTableName: true,
        });

        console.log(method)
        console.log(param)

        var result;

        if(method == 'get'){
            console.log('method - get')
            switch (param.action){
                case 'get_white_list':
                    console.log('get_white_list')
                    result = yield *getWhiteList();
                    break;
            }
        }else if(method == 'post'){
            console.log('method - post')
            switch (param.action){
                case 'set_white_list':
                    console.log('set_white_list')
                    result = yield *setWhiteList(param);
                    break;
            }
        }

        resp(null, result);

    })

    function *getWhiteList(){
        console.log('get_white_list---begin')
        var result = yield function (done) {
            db.t_misc_conf.findOne({where: {key: 'super_whitelist'}}).then(function (res) {
                console.log('get_white_list--yield--done')
                console.log(res.get('value'))
                done(null, {"ret": 0, msg: 'success', "result": res.get('value')});
            });
        }

        console.log('get_white_list---done')
        return result;
    }

    function *setWhiteList(param){
        console.log('set_white_list---begin')
        var data = {
            value: param.value
        }
        var result = yield function (done) {
            db.t_misc_conf.update(data, {where: {key: 'super_whitelist'}}).then(function (res) {
                console.log(res)
                done(null, {"ret": 0, msg: 'success'});
            });
        }
        console.log('set_white_list---done')
        return result;
    }



}
```
 



 

