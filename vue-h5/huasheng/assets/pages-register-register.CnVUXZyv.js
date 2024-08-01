import{T as e,as as a,V as o,U as l,ae as t,x as s,y as r,D as i,H as n,A as d,F as u,C as m,z as c,I as p,J as f,Y as g,aA as b,q as v,W as _,ax as w,X as h,ay as y,au as k,av as V,aw as C,at as j}from"./index-BrsO_Ji3.js";import{_ as U}from"./u-input.CX8rN-KB.js";import{r as q}from"./uni-app.es.ouLKK4z2.js";import{_ as B}from"./jp-verification-literalness.DCaFRf-L.js";import{_ as x,a as P}from"./u-form.B3wryjLU.js";import{_ as F}from"./u-button.k_6YtO4l.js";import{_ as A}from"./u-code.CVaNkk6b.js";import{_ as R}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.D7SiMdEc.js";import"./u-line.CQM3Ema6.js";import"./u-loading-icon.CJYNNQXZ.js";const z=R({__name:"register",setup(R){const z=e(),T=a(),$=o("mobile"),D=o(),G=o(),I=o(),W=o(),Z=o(50),E=o(),H=o(),J=l({canvasCode:"",mobile:"",email:"",sms_code:"",password:"",confirmPassword:"",invite_code:"",code:"",role:0,from_to:window.location.origin}),L={canvasCode:[{type:"string",required:!0,message:"请输入右侧的图形验证码",trigger:["blur"]},{validator:(e,a)=>a.toLocaleUpperCase()===G.value.identifyCode,message:"图形验证码不正确",trigger:["blur","change"]}],mobile:[{type:"string",required:!0,message:"请输入手机号",trigger:["blur"]},{validator:(e,a)=>uni.$u.test.mobile(a),message:"手机号码不正确",trigger:["blur"]}],email:[{type:"string",required:!0,message:"请输入邮箱地址",trigger:["blur"]},{validator:(e,a)=>uni.$u.test.email(a),message:"邮箱地址不正确",trigger:["blur"]}],sms_code:{type:"string",required:!0,message:"请输入手机验证码",trigger:["blur"]},code:{type:"string",required:!0,message:"请输入邮箱验证码",trigger:["blur"]},password:[{type:"string",required:!0,message:"请输入密码",trigger:["blur"]},{pattern:/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{6,16}$/,message:"密码长度为6-16位，必须包含数字和字母",trigger:["blur","change"]}],confirmPassword:[{type:"string",required:!0,message:"请输入确认密码",trigger:["blur"]},{validator:(e,a)=>a===J.password,message:"两次输入的密码不一致",trigger:["blur","change"]}]};function N(){D.value.validateField("mobile",(async e=>{if(e.length)return void(await v({title:e[0].message,icon:"none"}));if(!E.value.canGetCode)return void(await v({title:"倒计时结束后再发送",icon:"none"}));await _({title:"正在获取验证码"});const{error:a,message:o}=await w({code:"86",mobile:J.mobile});h(),a?await v({title:o,icon:"none"}):(await v({title:"验证码已发送",icon:"none"}),E.value.start())}))}function O(){D.value.validateField("email",(async e=>{if(e.length)return void(await v({title:e[0].message,icon:"none"}));if(!H.value.canGetCode)return void(await v({title:"倒计时结束后再发送",icon:"none"}));await _({title:"正在获取验证码"});const{error:a,message:o}=await y({email:J.email,captcha:"spew"});a?await v({title:o,icon:"none"}):(h(),a?await v({title:o,icon:"none"}):(await v({title:"验证码已发送",icon:"none"}),H.value.start()))}))}async function X(){try{await D.value.validate();const e="mobile"===$.value?k:V,a="mobile"===$.value?{...J,code:"86"}:J,{error:o,message:l,data:t}=await e({...a,form_to:T.availableDomain});if(o)return void(await v({title:l,icon:"none"}));z.setToken(t.token);const s=await C();z.setUserinfo(s.data),v({title:"登录成功！",duration:1e3}).then(),j()}catch(e){e.length&&await v({title:e[0].message,icon:"none"})}}return t($,(()=>{J.password=J.confirmPassword=""})),(e,a)=>{const o=m,l=q(s("up-input"),U),t=q(s("jp-verification-literalness"),B),v=q(s("up-form-item"),x),_=q(s("up-form"),P),w=q(s("up-button"),F),h=q(s("up-code"),A);return r(),i(u,null,[n(o,{class:"switch"},{default:d((()=>["mobile"===$.value?(r(),c(o,{key:0,class:"font-14-blue",onClick:a[0]||(a[0]=e=>$.value="email")},{default:d((()=>[p("切换到邮箱注册")])),_:1})):(r(),c(o,{key:1,class:"font-14-blue",onClick:a[1]||(a[1]=e=>$.value="mobile")},{default:d((()=>[p("切换到手机号注册")])),_:1}))])),_:1}),n(o,{class:"p-30"},{default:d((()=>[n(_,{class:"form","label-width":"120",labelPosition:"top",model:J,rules:L,ref_key:"formRef",ref:D},{default:d((()=>[n(v,{"left-icon":"account",label:"图形验证码",prop:"canvasCode","border-bottom":""},{right:d((()=>[n(t,{class:"ml-20",ref_key:"canvasRef",ref:G},null,512)])),default:d((()=>[n(l,{modelValue:J.canvasCode,"onUpdate:modelValue":a[2]||(a[2]=e=>J.canvasCode=e),border:"bottom",placeholder:L.canvasCode[0].message},null,8,["modelValue","placeholder"])])),_:1}),"mobile"===$.value?(r(),c(v,{key:0,"left-icon":"account",label:"手机号",prop:"mobile",borderBottom:""},{default:d((()=>[n(l,{modelValue:J.mobile,"onUpdate:modelValue":a[3]||(a[3]=e=>J.mobile=e),placeholder:L.mobile[0].message},null,8,["modelValue","placeholder"])])),_:1})):(r(),c(v,{key:1,"left-icon":"account",label:"邮箱地址",prop:"email",borderBottom:""},{default:d((()=>[n(l,{modelValue:J.email,"onUpdate:modelValue":a[4]||(a[4]=e=>J.email=e),placeholder:L.email[0].message},null,8,["modelValue","placeholder"])])),_:1})),"mobile"===$.value?(r(),c(v,{key:2,"left-icon":"lock",label:"手机验证码",prop:"sms_code",borderBottom:""},{default:d((()=>[n(l,{modelValue:J.sms_code,"onUpdate:modelValue":a[5]||(a[5]=e=>J.sms_code=e),placeholder:L.sms_code.message},{suffix:d((()=>[n(o,{class:"font-12-blue",onClick:N},{default:d((()=>[p(f(I.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1})):(r(),c(v,{key:3,"left-icon":"lock",label:"邮箱验证码",prop:"code",borderBottom:""},{default:d((()=>[n(l,{modelValue:J.code,"onUpdate:modelValue":a[6]||(a[6]=e=>J.code=e),placeholder:L.code.message},{suffix:d((()=>[n(o,{class:"font-12-blue",onClick:O},{default:d((()=>[p(f(W.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1})),n(v,{"left-icon":"lock",label:"密码",prop:"password",borderBottom:""},{default:d((()=>[n(l,{type:"password",modelValue:J.password,"onUpdate:modelValue":a[7]||(a[7]=e=>J.password=e),placeholder:L.password[0].message},null,8,["modelValue","placeholder"])])),_:1}),n(v,{"left-icon":"lock",label:"确认密码",prop:"confirmPassword",borderBottom:""},{default:d((()=>[n(l,{type:"password",modelValue:J.confirmPassword,"onUpdate:modelValue":a[8]||(a[8]=e=>J.confirmPassword=e),placeholder:L.confirmPassword[0].message},null,8,["modelValue","placeholder"])])),_:1}),n(v,{"label-width":"300","left-icon":"lock",label:"推荐人手机号码或邀请码（选填）",prop:"invite_code",borderBottom:""},{default:d((()=>[n(l,{modelValue:J.invite_code,"onUpdate:modelValue":a[9]||(a[9]=e=>J.invite_code=e),placeholder:"请输入推荐人手机号码或邀请码（选填）"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"]),n(w,{class:"mt-30",type:"primary",color:"linear-gradient(to right,#FF4B2B,#FF9C6E)",text:"注册",onClick:X}),n(h,{seconds:Z.value,ref_key:"verificationCodeRef",ref:E,onChange:a[10]||(a[10]=e=>I.value=e),"unique-key":"mobile"},null,8,["seconds"]),n(h,{seconds:Z.value,ref_key:"emailCodeRef",ref:H,onChange:a[11]||(a[11]=e=>W.value=e),"unique-key":"email"},null,8,["seconds"]),n(o,{class:"font-14-blue center-center mt-20",onClick:g(b)},{default:d((()=>[p("已有账户？去登录")])),_:1},8,["onClick"])])),_:1})],64)}}},[["__scopeId","data-v-4ac61274"]]);export{z as default};
