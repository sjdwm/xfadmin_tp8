/*!
 * js常用组件
 */
 
/*(function($){
  
})(jQuery);*/
//只上传图片用,url=上传后台地址,js_id上传按钮绑定,layui_size上传大小KB,ys_size压缩后的大小限制M
function up_img(url,js_id,layui_size=3048,ys_size=2){
  //上传图片
  layui.use(['form','upload'], function(){
  var form = layui.form
  ,upload = layui.upload; 
  form.render();//执行实例
  var uploadInst = upload.render({
    elem: js_id //绑定元素
    ,url: url //上传接口
    ,auto:false
    ,choose:function(obj){
                    var files = obj.pushFile();
                    var filesArry = [];
                    for (var key in files) { //将上传的文件转为数组形式
                      filesArry.push(files[key]);
                    }                   
                    var index = filesArry.length - 1;
                    var file = filesArry[index]; //获取最后选择的图片,即处理多选情况                    
                    if (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion.split(";")[1]
                        .replace(/[ ]/g, "").replace("MSIE", "")) < 9) {
                      return obj.upload(index, file);
                    }
                    canvasDataURL(file, function (blob) {
                      var aafile = new File([blob], file.name, {
                        type: file.type
                      })
                      var isLt1M;
                      if (file.size < aafile.size) {
                        isLt1M = file.size
                      } else {
                        isLt1M = aafile.size
                      } 
                      if (isLt1M / 1024 / 1024 > ys_size) {
                        return layer.alert('图片压缩之后还是大于'+ys_size+'M，请压缩之后上传！');
                      } else {
                        if (file.size < aafile.size) {
                          return obj.upload(index, file);
                        }
                        obj.upload(index, aafile);
                      }
                    })
                }
    ,done: function(res){
      //上传完毕回调
      //console.log(res);
      if(res.code==0){
        layer.msg('上传成功', {icon: 1});
        $("#img").attr("src",res.data.src);
      }else{
        layer.msg('上传失败', {icon: 5});
      }
    }
    ,size: layui_size //最大允许上传的文件大小
    ,accept: 'file' //允许上传的文件类型
    ,exts: 'png|jpg|jpeg|gif|bmp'
    ,error: function(){
      //请求异常回调
    }
  });

});
}
//上传图片压缩-开始
  function canvasDataURL(file, callback) { //压缩转化为base64
    var reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = function (e) {
      const img = new Image()
      const quality = 0.7 // 图像质量(压缩比)
      const canvas = document.createElement('canvas')
      const drawer = canvas.getContext('2d')
      img.src = this.result
      img.onload = function () {
        canvas.width = img.width
        canvas.height = img.height
        drawer.drawImage(img, 0, 0, canvas.width, canvas.height)
        convertBase64UrlToBlob(canvas.toDataURL(file.type, quality), callback);
      }
    }
  }  
  function convertBase64UrlToBlob(urlData, callback) { //将base64转化为文件格式
    const arr = urlData.split(',')
    const mime = arr[0].match(/:(.*?);/)[1]
    const bstr = atob(arr[1])
    let n = bstr.length
    const u8arr = new Uint8Array(n)
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n)
    }
    callback(new Blob([u8arr], {
      type: mime
    }));
 }
//上传图片压缩-结束
//手机号验证
    function validatemobile(mobile){
        if(mobile.length==0){
          layer.msg('请输入手机号码！', {icon: 5});
           document.form1.mobile.focus();
           return false;
        }

        if(mobile.length!=11){
            layer.msg('请输入有效的手机号码！', {icon: 5});
            document.form1.mobile.focus();
            return false;
        }

    var myreg = /^(((1[3-9]{1}))+\d{9})$/;
    if(!myreg.test(mobile)){
      layer.msg('请输入有效的手机号码！', {icon: 5});
        document.form1.mobile.focus();
        return false;
    }
}