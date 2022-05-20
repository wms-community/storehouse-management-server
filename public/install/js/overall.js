var url = window.location.protocol+'//'+window.location.host;
function ajax(options){
  options = options ||{};  //调用函数时如果options没有指定，就给它赋值{},一个空的Object
  options.type=(options.type || "GET").toUpperCase();/// 请求格式GET、POST，默认为GET
  options.dataType=options.dataType || "json";    //响应数据格式，默认json
  var params=formatParams(options.data);//options.data请求的数据
  var xhr;
  //考虑兼容性
  if(window.XMLHttpRequest){
      xhr=new XMLHttpRequest();
  }else if(window.ActiveObject){//兼容IE6以下版本
      xhr=new ActiveXobject('Microsoft.XMLHTTP');
  }
  //启动并发送一个请求
  if(options.type=="GET"){
      xhr.open("GET",options.url+"?"+params,true);
      xhr.send(null);
  }else if(options.type=="POST"){
      xhr.open("post",options.url,true);
      //设置表单提交时的内容类型
      //Content-type数据请求的格式
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(params);
  }
//    设置有效时间
  setTimeout(function(){
      if(xhr.readySate!=4){
          xhr.abort();
      }
  },options.timeout)
//    接收
//     options.success成功之后的回调函数  options.error失败后的回调函数
//xhr.responseText,xhr.responseXML  获得字符串形式的响应数据或者XML形式的响应数据
  xhr.onreadystatechange=function(){
      if(xhr.readyState==4){
          var status=xhr.status;
          if(status>=200&& status<300 || status==304){
            options.success&&options.success(xhr.responseText,xhr.responseXML);
          }else{
              options.error&&options.error(status);
          }
      }
  }
}
//格式化请求参数
function formatParams(data){
  var arr=[];
  for(var name in data){
      arr.push(encodeURIComponent(name)+"="+encodeURIComponent(data[name]));
  }
  arr.push(("v="+Math.random()).replace(".",""));
  return arr.join("&");

}
//版本号
ajax({
  url:url+"/install/version",
  type:'get',
  dataType:'json',
  timeout:10000,
  contentType:"application/json",
  success:function(res){
      var data = JSON.parse(res)
     
      if(data.status==200){
        var edition = document.getElementsByClassName('edition')
        for(var i=0;i<edition.length;i++){
          edition[i].innerHTML = data.data.version
        }
      }else{
        window.history.go(-1);
        alert(data.msg);
      }
　　　　　　//服务器返回响应，根据响应结果，分析是否登录成功
  },
  //异常处理
  error:function(e){
      console.log(e);
  }
})
//检测
var envs = document.getElementById('envs')
var modules = document.getElementById('modules')
var folders = document.getElementById('folders')
function testing(params) {
    envs.innerHTML = '<li class="table_title"><span>Environmental detection</span><span>Current state</span></li>'
    envs.innerHTML += '<li class="jc_loading"><span>All test items</span><span class="ztri"><img style="width:14px" src="/public/install/images/5-121204193R5-50.gif"></span></li>'
    modules.innerHTML = ' <li class="table_title"><span>Module detection</span><span></span></li>'
    modules.innerHTML += '<li class="jc_loading"><span>All test items</span><span class="ztri"><img style="width:14px" src="/public/install/images/5-121204193R5-50.gif"></span></li>'
    folders.innerHTML = '<li class="table_title"><span style="width: 50%;">Directory and file permission check</span><span style="padding-right: 40px;">Write</span><span style="padding-right: 15px;">Read</span></li>'
    folders.innerHTML += '<li class="jc_loading"><span>All test items</span><span class="ztri" style="margin-left:184px"><img src="/public/install/images/5-121204193R5-50.gif"></span><span class="ztri"><img style="width:14px" src="/public/install/images/5-121204193R5-50.gif"></span></li>'
    $('.jc-button').css({background:'#f1f1f1',color:'#999',borderColor:'#ddd',cursor:'not-allowed'}).attr({"disabled":"disabled"});
  ajax({
    url:url+"/install/envmonitor",
    type:'get',
    dataType:'json',
    timeout:10000,
    contentType:"application/json",
    success:function(res){
        var data = JSON.parse(res)
        if(data.status==200){
            $('.jc_loading').remove();
            $('.jc-button').removeAttr('style').removeAttr('disabled').css({background:'#3699FF',color:'white'});
            if(data.data.envs.length<=0){
              envs.innerHTML += '<li><span>All test items</span><span class="ztri"><img src="/public/install/images/dui.png" alt=""></span></li>'
            }else{
              data.data.envs.forEach(function(item,index){
                envs.innerHTML += '<li><span>'+item.name+'</span><span class="ztri"><img src="/public/install/images/cuo.png"></span></li>'
              });
              $('.nextStep').css({background:'#f1f1f1',color:'#999',borderColor:'#ddd',cursor:'not-allowed'}).attr({"disabled":"disabled","title":"Environmental error"});
            }
            if(data.data.modules.length<=0){
              modules.innerHTML += '<li><span>All test items</span><span class="ztri"><img src="/public/install/images/dui.png" alt=""></span></li>'
            }else{
              data.data.modules.forEach(function(item,index){
                modules.innerHTML += '<li><span>'+item.name+'</span><span class="ztri"><img src="/public/install/images/cuo.png" alt=""></span></li>'
              });
              $('.nextStep').css({background:'#f1f1f1',color:'#999',borderColor:'#ddd',cursor:'not-allowed'}).attr({"disabled":"disabled","title":"Environmental error"});
            }
            if(data.data.folders.length<=0){
              folders.innerHTML += '<li><span>All test items</span><span class="ztri" style="margin-left:184px"><img src="/public/install/images/dui.png"></span><span class="ztri"><img src="/public/install/images/dui.png"></span></li>'
            }else{
              data.data.folders.forEach(function(item,index){
                folders.innerHTML += '<li><span style="width: 40%;">'+item.name+'</span><span><img src="'+(item.write==''?'/public/install/images/dui.png':'/public/install/images/cuo.png')+'"></span><span class="ztri"><img src="'+(item.read=='unreadable'?'/public/install/images/cuo.png':'/public/install/images/dui.png')+'"></span></li>'
              });
              $('.nextStep').css({background:'#f1f1f1',color:'#999',borderColor:'#ddd',cursor:'not-allowed'}).attr({"disabled":"disabled","title":"Environmental error"});
            }
        }else{
          // alert('请求失败')
        }
  　　　　　　//服务器返回响应，根据响应结果，分析是否登录成功
    },
    //异常处理
    error:function(e){
        console.log(e);
    }
  })
}