var showBox = document.getElementsByClassName('show_box')   //步骤显示框
var nextSteps = document.getElementsByClassName('nextStep') //下一步
var lastStep = document.getElementsByClassName('lastStep')  //上一步
var databaseFiv = document.getElementsByClassName('databaseFiv') //数据库输入框
var inputList = document.getElementsByClassName('inputList')    //网站配置输入框
var daFail = document.getElementById('daFail')        //数据库 文字输入框错误提示
var rqstbc = document.getElementById('rqstbc')        //数据库 输入框错误提示显示隐藏
var tipsInfo = document.getElementById('tipsInfo')      //网站配置 文字输入框错误提示
var tipsInfoText = document.getElementById('tipsInfoText')    //网站配置 文字输入框错误提示
// var houzuiemail = document.getElementById('houzuiemail')    //电子邮件下拉选项
// var stepList = document.getElementById('stepList')          //安装步骤  读条
var stepList = $("#stepList")
var progress = document.getElementById('progress')          //进度条显示
var loginInput = document.getElementsByClassName('loginInput')  //登录部分的input
let sqlIndex = 0;
let totalIndex = 0;
stepList.append('<li><img src="/public/install/images/dui.png" alt=""><span>1.Start installation!</span></li>')
stepList.append('<li class="azgif"><img src="/public/install/images/5-121204193R5-50.gif"><span>Writing database configuration!</span></li>')
function randomWord(randomFlag, min, max) {
  arrNum = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
  let str = "",
  range = min,
  arr = [
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
    'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
    'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
  if (randomFlag) {
  range = Math.round(Math.random() * (max - min)) + min;// 任意长度
  }
  for (let i = 0; i < range; i++) {
    if(i == 0) {
      pos = Math.round(Math.random() * (arr.length - 10));
      str += arr[pos];
    }else if(i == 1){
      pos = Math.round(Math.random() * (arrNum.length - 1));
      str += arrNum[pos];
    } else {
      pos = Math.round(Math.random() * (arr.length - 1));
      str += arr[pos];
    }
  }
  return str;
  }
  $('#htlj').val(randomWord(false,8))
  $('#glyzh').val(randomWord(false,8))
  $('#pwd-rand').val(randomWord(false,12))
  $('.get-wzym').val(location.origin)
$('.rand-pwd-btn').click(function(){
  $('#pwd-rand').val(randomWord(false,12))
})
// 下一步
function xiayibu(index) {
  for(var j=0;j<showBox.length;j++){
    showBox[j].style.display = 'none'
  }
  showBox[index+1].style.display = 'block'
}
for(var i=0;i<nextSteps.length;i++){
  nextSteps[i].index = i;
  nextSteps[i].onclick = function (e) {
    var index = this.index
    switch (index) {
      case 0:
        xiayibu(index)
        testing()
      break;
      case 1:
        xiayibu(index)
      break;
      case 2:
        // 数据库输入框验证
        for(var l=databaseFiv.length-1;l>=0;l--){
          if(databaseFiv[l].value==''){
            switch (l) {
                case 5:
                  rqstbc.style.display = 'block'
                  daFail.innerHTML = 'Please enter data table prefix'
                break;
                case 4:
                  rqstbc.style.display = 'block'
                  daFail.innerHTML = 'Please enter a database password'
                break;
                case 3:
                  rqstbc.style.display = 'block'
                  daFail.innerHTML = 'Please enter a database username'
                break;
                case 2:
                  rqstbc.style.display = 'block'
                  daFail.innerHTML = 'Please enter a database name'
                break;
                case 1:
                  rqstbc.style.display = 'block'
                  daFail.innerHTML = 'Please enter the database port'
                break;
                case 0:
                  rqstbc.style.display = 'block'
                  daFail.innerHTML = 'Please enter the database server'
                break;
            }
          }else if(databaseFiv[0].value!=''&&databaseFiv[1].value!=''&&databaseFiv[2].value!=''&&databaseFiv[3].value!=''&&databaseFiv[4].value!=''&&databaseFiv[5].value!=''&&l==5){
            //数据库信息
            rqstbc.style.display = 'none'
            daFail.innerHTML = ''
            nextSteps[2].innerHTML = '<img src="/public/install/images/5-121204193R5-50.gif" class="loding">Next'
            ajax({
              url:url+"/install/dbmonitor",
              type:'post',
              data:{
                "hostname": databaseFiv[0].value,
                "hostport": databaseFiv[1].value,
                "dbname": databaseFiv[2].value,
                "username": databaseFiv[3].value,
                "password": databaseFiv[4].value,
                "prefix": databaseFiv[5].value,
              },
              dataType:'json',
              timeout:10000,
              contentType:"application/json",
              success:function(res){
                nextSteps[2].innerHTML = 'Next'
                  var data = JSON.parse(res)
                  if(data.status==200){
                    xiayibu(index)
                  }else{
                    rqstbc.style.display = 'block'
                    daFail.innerHTML = data.msg
                  }
            　　　//服务器返回响应，根据响应结果，分析是否登录成功
              },
              //异常处理
              error:function(e){
                rqstbc.style.display = 'block'
                daFail.innerHTML = 'Failed to connect to database!'
                nextSteps[2].innerHTML = 'Next'
              }
            })
          }
        }
        // xiayibu(index)
        break;
        case 3:
          //站点配置
          for(var l=inputList.length-1;l>=0;l--){
            if(inputList[l].value.match(/^[ ]*$/)){
              switch (l) {
                  case 0:
                    tipsInfo.style.display = 'block'
                    tipsInfoText.innerHTML = 'Please enter the system name!'
                  break;
                  case 1:
                    tipsInfo.style.display = 'block'
                    tipsInfoText.innerHTML = 'Please enter the website domain name!'
                  break;
                  case 2:
                    tipsInfo.style.display = 'block'
                    tipsInfoText.innerHTML = 'Please enter the administrator account!'
                  break;
                  case 3:
                  case 4:
                    tipsInfo.style.display = 'block'
                    tipsInfoText.innerHTML = 'Please input a password!'
                  break;
                  case 5:
                    tipsInfo.style.display = 'block'
                    tipsInfoText.innerHTML = 'Please input a Email!'
                  break;
              }
            }else if(inputList[4].value!=inputList[3].value){
              tipsInfo.style.display = 'block'
              tipsInfoText.innerHTML = 'The two passwords are inconsistent!'
            }else if(inputList[1].value!=''&&inputList[2].value!=''&&inputList[3].value!=''&&inputList[4].value!=''&&inputList[5].value!=''&&inputList[0].value!=''&&l==5){
              
                if(!new RegExp(/(http|https):\/\/([\w.]+\/?)\S*/).test(inputList[1].value) || inputList[1].value.substr(inputList[1].value.length - 1, 1) == '/'){
                  tipsInfo.style.display = 'block'
                  tipsInfoText.innerHTML = 'Please enter a legal website domain name, which must start with http(s):// and cannot end with /';
                  return;
                }
                // 网站配置
                tipsInfo.style.display = 'none'
                tipsInfoText.innerHTML = ''
                nextSteps[3].innerHTML = '<img src="/public/install/images/5-121204193R5-50.gif" class="loding">Next'
              //站点信息数据验证
              //导入数据库
              //设置信息
              //完成
                ajax({
                  url:url+'/install/codemonitor',
                  type:'POST',
                  dataType:'json',
                  data:{"license":inputList[0].value
                        ,"sitename": inputList[0].value,
                        "domain": inputList[1].value,
                        // "admin_application": inputList[2].value,
                        "manager": inputList[2].value,
                        "manager_pwd": inputList[3].value,
                        "manager_ckpwd": inputList[4].value,
                        "manager_email": inputList[5].value,
                  },
                  async:false,
                  timeout:10000,
                  contentType:"application/json",
                  success:function(res){
                    var data = JSON.parse(res)
                      if(data.status==200){
                        //请求导入数据库
                        xiayibu(index);
                        installSql();
                      }else{
                        tipsInfo.style.display = 'block'
                        nextSteps[3].innerHTML = 'Next'
                        tipsInfoText.innerHTML = data.msg
                      }

                  },
                })

                
            }
          }
        break;
    }
  }
}
// 上一步
function shangyibu(index) {
  if(index===0){
    testing()
  }
  if(index>0&&index<4){
    for(var j=0;j<showBox.length;j++){
      showBox[j].style.display = 'none'
    }
    showBox[index].style.display = 'block'
  }
}
for(var i=0;i<lastStep.length;i++){
  lastStep[i].index = i;
  lastStep[i].onclick= function (params) {
    var index = this.index
    shangyibu(index)  
  }
}
// 密码显示
var passShow = document.getElementsByClassName('showBtn')
var passwordInput = document.getElementsByClassName('password')
for(var i=0;i<passShow.length;i++){
  passShow[i].index = i 
  passShow[i].onclick = function (params) {
    var index = this.index
    if (passwordInput[index].type == "password") {
      passwordInput[index].type = "text";
     }else {
      passwordInput[index].type = "password";
     }
  }
}
 function ImplementationSteps(num) {
  // 进行安装
    // 初始化赋值
    // let sqlIndex=add()
    // sessionStorage.setItem('num',sqlIndex)
    // 通过 num判断执行多少次sql接口  num 通过上一个接口返回
    ajax({
      url:url+'/install/installing',
      type:'POST',
      dataType:'json',
      // 作为动态传参这个动态递增到25
      data:{"sql_index":num},
      async:false,
      timeout:100000,
      contentType:"application/json",
      success:function(res){
        let installData = JSON.parse(res);
        if(installData.status==200){
          if(installData.msg == "Installation complete!"){
            stepList.find('.azgif').remove();
            ajax({
              url:url+'/install/setdbconfig',
              type:'POST',
              dataType:'json',
              async:false,
              timeout:10000,
              contentType:"application/json",
              success:function(res){
                var data = JSON.parse(res)
                  if(data.status==200){
                    stepList.append('<li><img src="/public/install/images/dui.png"><span>3.'+data.msg+'</span></li><li><img src="/public/install/images/5-121204193R5-50.gif" alt=""><span>4.Write data!</span></li>')
                    stepList.scrollTop(stepList.prop("scrollHeight"))
                    progress.style.width = '74%'
                    ajax({
                      url:url+'/install/setsite',
                      type:'POST',
                      dataType:'json',
                      async:false,
                      timeout:100000,
                      contentType:"application/json",
                      success:function(res){
                        var data = JSON.parse(res)
                          if(data.status==200){
                            stepList.append('<li><img src="/public/install/images/dui.png"><span>4.'+data.msg+'</span></li><li><img src="./static/images/5-121204193R5-50.gif" alt=""><span>5.Write hook!</span></li>')
                            stepList.scrollTop(stepList.prop("scrollHeight"))
                             progress.style.width = '80%'
                            ajax({
                              url:url+'/install/installapphooks',
                              type:'POST',
                              dataType:'json',
                              async:false,
                              timeout:10000,
                              contentType:"application/json",
                              success:function(res){
                                var data = JSON.parse(res)
                                  if(data.status==200){
                                    stepList.append('<li><img src="/public/install/images/dui.png"><span>5.'+data.msg+'</span></li><li><img src="/public/install/images/dui.png"><span>6.Writing event!</span></li>')
                                    stepList.scrollTop(stepList.prop("scrollHeight"))
                                    progress.style.width = '87%'
                                    ajax({
                                      url:url+'/install/installappuseractions',
                                      type:'POST',
                                      dataType:'json',
                                      async:false,
                                      timeout:10000,
                                      contentType:"application/json",
                                      success:function(res){
                                        var data = JSON.parse(res)
                                          if(data.status==200){
                                            stepList.append('<li><img src="/public/install/images/dui.png"><span>6.'+data.msg+'</span></li><li><img src="/public/install/images/dui.png"><span>7.Step detection and locking is in progress!</span></li>')
                                            stepList.scrollTop(stepList.prop("scrollHeight"))
                                            progress.style.width = '94%'

                                            ajax({
                                              url:url+'/install/stepLast',
                                              type:'POST',
                                              dataType:'json',
                                              async:false,
                                              timeout:10000,
                                              contentType:"application/json",
                                              success:function(res){
                                                var data = JSON.parse(res)
                                                  if(data.status==200){
                                                    stepList.append('<li><img src="/public/install/images/dui.png"><span>7.'+data.msg+'</span></li>')
                                                    stepList.scrollTop(stepList.prop("scrollHeight"))

                                                    loginInput[0].value = data.data.admin_url
                                                    loginInput[1].value = data.data.admin_name
                                                    loginInput[2].value = data.data.admin_pass
                                                   
                                                    progress.style.width = '100%'
                                                    setTimeout(() => {
                                                      xiayibu(4)
                                                    }, 1000);
                                                  }else{ 
                                                    stepList.append('<li><img src="/public/install/images/dui.png"><span>7.'+data.msg+'</span></li>')
                                                    stepList.scrollTop(stepList.prop("scrollHeight"))
                                                  }
                                          
                                              },
                                              //异常处理
                                              error:function(e){
                                                  console.log(e);
                                              }
                                            })
                          
                                          }else{
                                            stepList.append('<li><img src="/public/install/images/cuo.png"><span>6.'+data.msg+'</span></li>')
                                            stepList.scrollTop(stepList.prop("scrollHeight"))
                                          }
                                  
                                      },
                                      //异常处理
                                      error:function(e){
                                          console.log(e);
                                      }
                                    })
                                  }else{
                                    stepList.append('<li><img src="/public/install/images/cuo.png" alt=""><span>5.'+data.msg+'</span></li>')
                                    stepList.scrollTop(stepList.prop("scrollHeight"))
                                  }
                              },
                              //异常处理
                              error:function(e){
                                  console.log(e);
                              }
                            })
                          }else{
                            stepList.append('<li><img src="/public/install/images/cuo.png" alt=""><span>4.'+data.msg+'</span></li>')
                            stepList.scrollTop(stepList.prop("scrollHeight"))
                          }
                      },
                      //异常处理
                      error:function(e){
                          console.log(e);
                      }
                    })
                  }else{
                    stepList.append('<li><img src="/public/install/images/cuo.png"><span>3.'+data.msg+'</span></li>')
                    stepList.scrollTop(stepList.prop("scrollHeight"))
                  }
              },
              //异常处理
              error:function(e){
                  console.log(e);
              }
            })
          }
          else{
            sqlIndex++;
            stepList.find('.azgif').remove();
            stepList.append('<li><img src="/public/install/images/dui.png"><span>'+installData.msg+'</span></li>')
            stepList.append('<li class="azgif"><img src="/public/install/images/5-121204193R5-50.gif"><span>Writing database configuration!</span></li>')
            progress.style.width = ""+(70/totalIndex)*sqlIndex+"%"
            stepList.scrollTop(stepList.prop("scrollHeight"))
            ImplementationSteps(sqlIndex);
          }
        }else{
          stepList.append('<img src="/public/install/images/cuo.png"><span>SQL execution failed</span>')
        }
      },
      //异常处理
      error:function(e){
          console.log(e);
      }
    })
}

function installSql(num,k){
  stepList.find('.azgif').remove();
  if (!num)
    num = 0;
  if (!k)
    k = 2;
  ajax({
    url:url+'/install/installing',
    type:'POST',
    dataType:'json',
    // 作为动态传参这个动态递增到25
    data:{
      "line": num
    },
    async:false,
    timeout:100000,
    contentType:"application/json",
    success:function(res){
      res = JSON.parse(res);
      if (400 === res.status){
        if (res.msg){
          if(res.data.run){
            stepList.append('<li><img src="/public/install/images/dui.png"><span>' + k + '.' + res.msg + '</span></li>')
          }else{
            stepList.append('<li><img src="/public/install/images/cuo.png"><span>' + k + '.' + res.msg +'</span></li>')
          }
          stepList.scrollTop(stepList.prop("scrollHeight"));
          ++k;
        }
        setTimeout("installSql("+res.num+","+k+")",200);
      }else if (200 === res.status){
        installComplete();
      }else{
        stepList.append('<li><img src="/public/install/images/cuo.png"><span>Error !</span></li>')
        stepList.scrollTop(stepList.prop("scrollHeight"))
      }
    },
    error:function (e) {
      console.log(e);
    }
  });
}
function installComplete(){
  ajax({
    url:url+"/install/complete",
    type:'post',
    data:{
      "sitename": inputList[0].value,
      "domain": inputList[1].value,
      "manager": inputList[2].value,
      "manager_pwd": inputList[3].value,
      "manager_ckpwd": inputList[4].value,
      "manager_email": inputList[5].value,
    },
    dataType:'json',
    timeout:10000,
    contentType:"application/json",
    success:function(res){
      nextSteps[3].innerHTML = 'Next'
      var data = JSON.parse(res)
      if(data.status==200){
        loginInput[0].value = data.data.admin_url
        loginInput[1].value = data.data.admin_name
        loginInput[2].value = data.data.admin_pass

        progress.style.width = '100%'
        setTimeout(() => {
          xiayibu(4)
        }, 1000);
      }else{
        tipsInfo.style.display = 'block'
        tipsInfoText.innerHTML = data.msg
      }
      //服务器返回响应，根据响应结果，分析是否登录成功
    },
    //异常处理
    error:function(e){
      tipsInfo.style.display = 'block'
      tipsInfoText.innerHTML = 'Configuration failed!'
      nextSteps[3].innerHTML = 'Next'
    }
  })
}