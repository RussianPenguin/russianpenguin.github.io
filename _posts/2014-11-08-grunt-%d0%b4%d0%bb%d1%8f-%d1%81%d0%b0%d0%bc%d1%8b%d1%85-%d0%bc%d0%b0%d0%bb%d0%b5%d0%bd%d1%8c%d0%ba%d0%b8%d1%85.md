---
layout: post
title: Grunt для самых маленьких
date: 2014-11-08 16:06:39.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- css
- grunt
- javascript
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/11/08/grunt-%d0%b4%d0%bb%d1%8f-%d1%81%d0%b0%d0%bc%d1%8b%d1%85-%d0%bc%d0%b0%d0%bb%d0%b5%d0%bd%d1%8c%d0%ba%d0%b8%d1%85/"
---
На проекте есть папка с с js и стилями css.

Нужно все это минифицировать.

### 1 - нам нужен грант (установленный локально).

Предполагается, что глобально грант уже установлен.

Если нет, то

[code language="shell"] $ sudo yum install nodejs-grunt\*[/code]

Ставим нужное

[code language="shell"] $ npm install grunt  
$ npm-install grunt-contrib-unglify grunt-contrib-watch grunt-contrib-cssmin grunt-contrib-concat[/code]

Если не поставить модули локально, то получим ошибку

```
Unable to find local grunt
```

### 2 - создаем файл с описанием проекта package.json

[code language="javascript"]{  
 "name": "\<project name\>",  
 "version": "0.1.0",  
 "devDependencies": {  
 "grunt": "~0.4.5",  
 "grunt-contrib-concat": "^0.4.0",  
 "grunt-contrib-cssmin": "^0.10.0",  
 "grunt-contrib-uglify": "^0.5.0",  
 "grunt-contrib-watch": "\*"  
 },  
 "dependencies": {  
 "grunt": "^0.4.5",  
 "grunt-ts": "^1.11.13"  
 }  
}[/code]

### 3 - создаем сценарий для работы

[code language="javascript"]module.exports = function (grunt) {  
 // 1 - Описываем все выполняемые задачи  
 grunt.initConfig({  
 pkg: grunt.file.readJSON('package.json'),  
 concat: {  
 css: {  
 src: ['src/\*\*/\*.css'],  
 dest: 'dist/app.css'  
 },  
 js: {  
 src: ['src/js/\*\*/\*.js'],  
 dest: 'dist/app.js'  
 }  
 },  
 cssmin: {  
 css: {  
 src: 'dist/app.css',  
 dest: 'dist/app.min.css'  
 }  
 },  
 uglify: {  
 js: {  
 src: 'dist/app.js',  
 dest: 'dist/app.min.js'  
 }  
 },  
 watch: {  
 css: {  
 files: ['src/css/\*\*/\*.css'],  
 tasks: ['concat:css', 'cssmin:css']  
 },  
 js: {  
 files: ['src/js/\*\*/\*.js'],  
 tasks: ['concat:js', 'uglify:js']  
 }  
 }  
 });

// 2 - Загружаем нужные плагины  
 grunt.loadNpmTasks('grunt-contrib-concat');  
 grunt.loadNpmTasks('grunt-contrib-cssmin');  
 grunt.loadNpmTasks('grunt-contrib-uglify');  
 grunt.loadNpmTasks('grunt-contrib-watch');

// 3 - Говорим grunt, что мы хотим сделать, когда напечатаем grunt в терминале.  
 grunt.registerTask('default', ['concat', 'cssmin', 'uglify']);

};[/code]

### 4 - печатаем grunt в терминали

[code langulage="shell"]$ grunt  
Running "concat:js" (concat) task  
File gapi.js created.

Running "uglify:js" (uglify) task  
\>\> 1 file created.

Done, without errors.[/code]

