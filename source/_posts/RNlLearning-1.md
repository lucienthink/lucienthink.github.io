title: React Native笔记-1
date: 2016-05-30 11:11
cover_index: /img/RNLearning-cover_index-1.jpeg
cover_detail: /img/RNLearning-cover_detail.png
tags: React Native
comments: true
---

# 环境依赖

本文所写依赖mac环境
```
brew install node         // 建议5.0以上
brew install watchman     // 监听文件更新
brew install flow         // 类型检测,可不安装
```

## iOS

Xcode 7.x

## Android

[JDK](http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html)

Android SDK
```
brew install android-sdk  // 安装 Android SDK
sudo vi ~/.bashrc         // 编辑环境变量
```
.bashrc 文件写入以下内容
```
export ANDROID_HOME=/usr/local/opt/android-sdk
```
终端输入 android , 打开后下载最新 tools, API, Lib


# 创建项目

```
npm install -g react-native-cli    // 安装react-native 命令
react-native init beyondpenguin    // 初始化工程
```

创建完成后,大致目录结构如下图
![目录](/img/RNLearning-1.png)

index.ios.js 是 iOS 的入口文件
index.android.js 是 Android 的入口文件
index.web.js 是用了[react-web](https://github.com/taobaofed/react-web)后可以将react项目打包为H5页面
server.js 是我用来模拟fetch数据起的一个express服务文件

我们的项目开发在 app 目录下

在看 app 目录之前建议先学习 [redux](http://cn.redux.js.org/)

再来看看我们项目中都用到了哪些好用的组件,贴出我的 package.json

```
{
  "name": "beyondpenguin",
  "version": "0.0.1",
  "private": true,
  "scripts": {
    "start": "node node_modules/react-native/local-cli/cli.js start"
  },
  "dependencies": {
    "i": "^0.3.4",
    "react": "^0.14.7",
    "react-dom": "^0.14.7",
    "react-native": "^0.22.2",
    "react-native-vector-icons": "^2.0.2",
    "react-redux": "^4.4.1",
    "react-web": "^0.2.4",
    "redux": "^3.3.1",
    "redux-thunk": "^2.0.1",
    "rn-viewpager": "^1.1.1",
    "rnpm": "^1.5.3"
  },
  "devDependencies": {
    "babel": "^6.5.2",
    "babel-core": "5.8.19",
    "babel-loader": "^5.1.3",
    "haste-resolver-webpack-plugin": "^0.1.2",
    "json-loader": "^0.5.2",
    "react-hot-loader": "^1.2.7",
    "webpack": "^1.12.10",
    "webpack-dev-server": "^1.14.0",
    "webpack-html-plugin": "^0.1.1"
  }
}
```
说下几个好用的:

* [react-native-vector-icons](https://github.com/oblador/react-native-vector-icons), 一个icon集合的库, 对于开发demo的我们来说, 非常好用

* [redux-thunk](https://github.com/gaearon/redux-thunk), redux异步 action 的组件

* [rnpm](https://github.com/rnpm/rnpm), React Native Package Manager

* 另推荐个发布 RN 组件的站, [js.coach](https://js.coach/), 有很多第三方的组件, 帮助我们兼容iOS和Android组件的差异