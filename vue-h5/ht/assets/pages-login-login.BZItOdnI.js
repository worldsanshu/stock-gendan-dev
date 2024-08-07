import{V as e,T as a,aa as l,u as o,as as t,U as s,ae as r,x as i,y as d,z as n,A as u,$ as c,B as m,C as A,H as f,Y as p,I as g,J as w,a9 as b,G as v,r as y,at as k,au as h,q as V,av as C,W as z,aw as U,X as B,ax as j,ay as q,N as X}from"./index-DugkB_-4.js";import{_ as H}from"./u-input.UpaywbSH.js";import{e as F,r as R}from"./uni-app.es.CX-CC-lz.js";import{_ as x,a as G}from"./u-form.MzjK-z7U.js";import{_ as Y}from"./jp-verification-literalness.D3AZPIc5.js";import{_ as I}from"./u-button.D_9ELcMi.js";import{_ as N}from"./u-code.zWmIK01X.js";import{_ as L}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.Ca1c56qc.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";const E=L({__name:"login",setup(L){const E=e(),Q=a(),S=l(),M=o();setTimeout((()=>{Q.token&&t()}));const P=e("mobile"),W=e(),T=e(),D=e(0),K=e(),J=e(50),O=e(),_=e(),Z=e(),$=s({mobile:"",password:"",from_to:window.location.origin}),ee=s({canvasCode:"",mobile:"",email:"",sms_code:"",password:"",confirmPassword:"",invite_code:"",code:"",role:0,from_to:window.location.origin}),ae={canvasCode:[{type:"string",required:!0,message:"请输入右侧的图形验证码",trigger:["blur"]},{validator:(e,a)=>a.toLocaleUpperCase()===W.value.identifyCode,message:"图形验证码不正确",trigger:["blur","change"]}],mobile:[{type:"string",required:!0,message:"请输入手机号",trigger:["blur"]},{validator:(e,a)=>uni.$u.test.mobile(a),message:"手机号码不正确",trigger:["blur"]}],email:[{type:"string",required:!0,message:"请输入邮箱地址",trigger:["blur"]},{validator:(e,a)=>uni.$u.test.email(a),message:"邮箱地址不正确",trigger:["blur"]}],sms_code:{type:"string",required:!0,message:"请输入手机验证码",trigger:["blur"]},code:{type:"string",required:!0,message:"请输入邮箱验证码",trigger:["blur"]},password:[{type:"string",required:!0,message:"请输入密码",trigger:["blur"]},{pattern:/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{6,16}$/,message:"密码长度为6-16位，必须包含数字和字母",trigger:["blur","change"]}],confirmPassword:[{type:"string",required:!0,message:"请输入确认密码",trigger:["blur"]},{validator:(e,a)=>a===ee.password,message:"两次输入的密码不一致",trigger:["blur","change"]}]},le={mobile:{type:"string",required:!0,message:"请输入手机号/邮箱",trigger:["blur","change"]},password:{type:"string",required:!0,message:"请输入密码",trigger:["blur","change"]}};async function oe(){try{await E.value.validate();const e="mobile"===P.value?k:h,a="mobile"===P.value?{...ee,code:"86"}:ee,{error:l,message:o,data:s}=await e({...a,form_to:S.availableDomain});if(l)return void(await V({title:o,icon:"none"}));Q.setToken(s.token);const r=await C();Q.setUserinfo(r.data),V({title:"登录成功！",duration:1e3}).then(),t()}catch(e){e.length&&await V({title:e[0].message,icon:"none"})}}function te(){E.value.validateField("mobile",(async e=>{if(e.length)return void(await V({title:e[0].message,icon:"none"}));if(!O.value.canGetCode)return void(await V({title:"倒计时结束后再发送",icon:"none"}));await z({title:"正在获取验证码"});const{error:a,message:l}=await U({code:"86",mobile:ee.mobile});B(),a?await V({title:l,icon:"none"}):(await V({title:"验证码已发送",icon:"none"}),O.value.start())}))}function se(){E.value.validateField("email",(async e=>{if(e.length)return void(await V({title:e[0].message,icon:"none"}));if(!_.value.canGetCode)return void(await V({title:"倒计时结束后再发送",icon:"none"}));await z({title:"正在获取验证码"});const{error:a,message:l}=await j({email:ee.email,captcha:"spew"});a?await V({title:l,icon:"none"}):(B(),a?await V({title:l,icon:"none"}):(await V({title:"验证码已发送",icon:"none"}),_.value.start()))}))}async function re(){try{await E.value.validate();const{error:e,message:a,data:l}=await q({...$,form_to:S.availableDomain});if(e)return void(await V({title:a,icon:"none"}));Q.setToken(l.token);const o=await C();Q.setUserinfo(o.data),V({title:"登录成功！",duration:1e3}).then(),t()}catch(e){e.length&&await V({title:e[0].message,icon:"none"})}}return r(P,(()=>{ee.password=ee.confirmPassword=""})),async function(){const{data:e}=await c();Z.value=e.module}(),F((()=>!Q.token)),(e,a)=>{const l=m,o=X,t=R(i("up-input"),H),s=R(i("up-form-item"),x),r=R(i("up-form"),G),c=R(i("jp-verification-literalness"),Y),k=R(i("up-button"),I),h=R(i("up-code"),N);return A(),d(l,{class:u(["login-root",0===D.value?"login-root-loin1":"login-root-loin2"])},{default:n((()=>[f(l,{style:{height:"90rpx"}}),f(l,{class:"ico"},{default:n((()=>[f(o,{src:p(M).appConfig.logo,alt:""},null,8,["src"])])),_:1}),f(l,{class:"welcome"},{default:n((()=>[g(w(p(M).appConfig.web_site_title||""),1)])),_:1}),f(l,{class:"muns"},{default:n((()=>[f(l,{class:"mun-top"},{default:n((()=>[f(l,{class:"mun-top-lse",onClick:a[0]||(a[0]=e=>D.value=0),style:b(0===D.value?" background-color:rgba(253,241,239);":"")},{default:n((()=>[f(l,{class:"siz",style:b(0===D.value?" font-weight: 600;":"font-weight: 500;")},{default:n((()=>[g("登录")])),_:1},8,["style"])])),_:1},8,["style"]),0===D.value?(A(),d(o,{key:0,src:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADgAAAAwCAMAAABzC0lBAAAAAXNSR0IB2cksfwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAOFQTFRF////////////////////////////////////////AAAA////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8X3vBAAAAEt0Uk5TzczDtaeWdlUrAwCMTw3HgiaPIcprBq0nxUp0cFMztw6IOKxXughhv8QTdcgZgMuLKZU0nz6qAr4RjlHGLLsYsBavF7kqThBbBLFUUgsk4gAAAQZJREFUeJyd1N0rwwEYxfFz3KGUJi20ZtOmaV5mki0tufJviyVJSPOyaLKXWF6WSEr5XWkX+9l8z+3zfO6e51jyb/SPdC0PDdvfBAYZs78QDDLuTwYVsd8RlCb9xqCi7jAoTXxAqKlXCDX9AqFmniGMjDwxqJjbDCr+CKFmHyBMtMLHf/9gsgXhnBsMKlWHMN0M64R+PRN67f3gvO8YVKYG4YJvGVT2BsLFes/aG6C9l6oQavkawpUrCJW7hHD1AkLlKxCu+ZzBHh89IFz3GYPaOIVQhRMIiz5mUJs+YlAlHzKoLR8wqG2XGVQ0u8+gNFrcY1DpuHcRlHZcbSAYXO59poxgkNgPyUw0JfXpZqwAAAAASUVORK5CYII=",mode:""})):v("",!0),1===D.value?(A(),d(o,{key:1,src:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADoAAAAwCAMAAAB3/pl8AAAAAXNSR0IB2cksfwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAOFQTFRFAAAA////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////rKoC8wAAAEt0Uk5TAB9LdJiryc0xeLIHV7ACVrsnpGULjxOqF68PBp5+RBS/gy7MoD4BqUkDsVO4Xr5oEcNyFsd8HsqHK04YwZt3VE1PbRCQIZIphFhwyig7swAAATRJREFUeJyd1v8rg1EUBnDPb2q1WtbShCYTSZNFWPmalP9aUkMiIoxoW2QrRFJKFKn3y73nPPecn97O837ebp176kVPWOGvfh9DXC/wFfkK7TLAZ/wAJMwCH4kWSXN4T/U42vfmeI+heby62gQt4MXZ12k/ntyBSovfHqnSAXR9kUYHO95IoUOP/kymw3iw0tK9EIp0BG0rHW1JqUTLTUlKdAx3Vjp+K0qBTuDGSievZemnmVLDSqeuFOml+eKllVYuNOmj0zi30pkzVXpoFadWOnuiSzedw7GVzh8R0kkXOtKGi1TecIkuHlLSQWs4MNJsZZ+Tabq0R8oUXa6zMklXWtRcHHS1ycs4XdvlYYyuYydERmi5m/wxIukGtsPgP93EM7NmKVotNLj7nqRb9VzAQKL1A23nOIG+VgmNAAAAAElFTkSuQmCC",mode:""})):v("",!0),f(l,{class:"mun-top-rse",onClick:a[1]||(a[1]=e=>D.value=1),style:b(1===D.value?" background-color:rgba(253,241,239);":"")},{default:n((()=>[f(l,{class:"siz",style:b(1===D.value?" font-weight: 600;":"font-weight: 500;")},{default:n((()=>[g("注册")])),_:1},8,["style"])])),_:1},8,["style"])])),_:1}),f(l,{class:"area"},{default:n((()=>[f(l,{style:{height:"50rpx"}}),0===D.value?(A(),d(l,{key:0,class:"area-lse"},{default:n((()=>[f(r,{class:"form","label-width":"120",labelPosition:"top",model:$,rules:le,ref_key:"formRef",ref:E},{default:n((()=>[f(s,{label:"手机号/邮箱",prop:"mobile",borderBottom:"",ref:"item1"},{default:n((()=>[f(t,{modelValue:$.mobile,"onUpdate:modelValue":a[2]||(a[2]=e=>$.mobile=e),style:{"background-color":"#fff"},placeholder:"请输入手机号/邮箱"},null,8,["modelValue"])])),_:1},512),f(s,{label:"密码",prop:"password",borderBottom:"",ref:"item1"},{default:n((()=>[f(t,{type:"password",style:{"background-color":"#fff"},modelValue:$.password,"onUpdate:modelValue":a[3]||(a[3]=e=>$.password=e),placeholder:"请输入密码"},null,8,["modelValue"])])),_:1},512)])),_:1},8,["model"])])),_:1})):v("",!0),1===D.value?(A(),d(l,{key:1,class:"area-lse"},{default:n((()=>[Z.value&&1===Z.value.email_register?(A(),d(l,{key:0,class:"switch"},{default:n((()=>["mobile"===P.value?(A(),d(l,{key:0,class:"font-14-blue",onClick:a[4]||(a[4]=e=>P.value="email")},{default:n((()=>[g("切换到邮箱注册")])),_:1})):(A(),d(l,{key:1,class:"font-14-blue",onClick:a[5]||(a[5]=e=>P.value="mobile")},{default:n((()=>[g("切换到手机号注册")])),_:1}))])),_:1})):v("",!0),f(r,{class:"form","label-width":"120",labelPosition:"top",model:ee,rules:ae,ref_key:"formRef",ref:E},{default:n((()=>[f(s,{label:"图形验证码",prop:"canvasCode","border-bottom":""},{right:n((()=>[f(c,{class:"ml-20",ref_key:"canvasRef",ref:W},null,512)])),default:n((()=>[f(t,{modelValue:ee.canvasCode,"onUpdate:modelValue":a[6]||(a[6]=e=>ee.canvasCode=e),style:{"background-color":"#fff"},border:"bottom",placeholder:ae.canvasCode[0].message},null,8,["modelValue","placeholder"])])),_:1}),"mobile"===P.value?(A(),d(s,{key:0,label:"手机号",prop:"mobile",borderBottom:""},{default:n((()=>[f(t,{modelValue:ee.mobile,"onUpdate:modelValue":a[7]||(a[7]=e=>ee.mobile=e),style:{"background-color":"#fff"},placeholder:ae.mobile[0].message},null,8,["modelValue","placeholder"])])),_:1})):(A(),d(s,{key:1,label:"邮箱地址",prop:"email",borderBottom:""},{default:n((()=>[f(t,{modelValue:ee.email,"onUpdate:modelValue":a[8]||(a[8]=e=>ee.email=e),style:{"background-color":"#fff"},placeholder:ae.email[0].message},null,8,["modelValue","placeholder"])])),_:1})),"mobile"===P.value?(A(),d(s,{key:2,label:"手机验证码",prop:"sms_code",borderBottom:""},{default:n((()=>[f(t,{modelValue:ee.sms_code,"onUpdate:modelValue":a[9]||(a[9]=e=>ee.sms_code=e),style:{"background-color":"#fff"},placeholder:ae.sms_code.message},{suffix:n((()=>[f(k,{onClick:te,text:T.value,type:"success",size:"mini"},{default:n((()=>[g(w(T.value),1)])),_:1},8,["text"])])),_:1},8,["modelValue","placeholder"])])),_:1})):(A(),d(s,{key:3,label:"邮箱验证码",prop:"code",borderBottom:""},{default:n((()=>[f(t,{modelValue:ee.code,"onUpdate:modelValue":a[10]||(a[10]=e=>ee.code=e),style:{"background-color":"#fff"},placeholder:ae.code.message},{suffix:n((()=>[f(k,{onClick:se,text:K.value,type:"success",size:"mini"},{default:n((()=>[g(w(K.value),1)])),_:1},8,["text"])])),_:1},8,["modelValue","placeholder"])])),_:1})),f(s,{label:"密码",prop:"password",borderBottom:""}),f(t,{type:"password",modelValue:ee.password,"onUpdate:modelValue":a[11]||(a[11]=e=>ee.password=e),placeholder:ae.password[0].message},null,8,["modelValue","placeholder"]),f(s,{label:"确认密码",prop:"confirmPassword",borderBottom:""},{default:n((()=>[f(t,{type:"password",modelValue:ee.confirmPassword,"onUpdate:modelValue":a[12]||(a[12]=e=>ee.confirmPassword=e),placeholder:ae.confirmPassword[0].message},null,8,["modelValue","placeholder"])])),_:1}),f(s,{"label-width":"300",label:"推荐人手机号码或邀请码（选填）",prop:"invite_code",borderBottom:""},{default:n((()=>[f(t,{style:{"background-color":"#fff"},modelValue:ee.invite_code,"onUpdate:modelValue":a[13]||(a[13]=e=>ee.invite_code=e),placeholder:"请输入推荐人手机号码或邀请码（选填）"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"])])),_:1})):v("",!0),0===D.value?(A(),d(l,{key:2,class:"login-button",onClick:re},{default:n((()=>[g("登录")])),_:1})):v("",!0),f(l,{style:{height:"30rpx"}})])),_:1}),f(h,{seconds:J.value,ref_key:"verificationCodeRef",ref:O,onChange:a[14]||(a[14]=e=>T.value=e),"unique-key":"mobile"},null,8,["seconds"]),f(h,{seconds:J.value,ref_key:"emailCodeRef",ref:_,onChange:a[15]||(a[15]=e=>K.value=e),"unique-key":"email"},null,8,["seconds"])])),_:1}),1===D.value?(A(),d(l,{key:0,class:"register-button",onClick:oe},{default:n((()=>[g("注册")])),_:1})):v("",!0),f(l,{class:"munkf",onClick:a[16]||(a[16]=e=>async function(e){y({url:e})}("/pages/customer-service/customer-service"))},{default:n((()=>[f(o,{src:"data:image/png;base64,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",alt:""}),f(l,{class:"mufiz"},{default:n((()=>[g("客服")])),_:1})])),_:1}),f(l,{style:{height:"30rpx"}})])),_:1},8,["class"])}}},[["__scopeId","data-v-bd2cf368"]]);export{E as default};
