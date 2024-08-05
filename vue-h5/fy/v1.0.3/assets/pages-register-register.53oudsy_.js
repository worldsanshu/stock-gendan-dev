import{T as e,as as a,V as o,U as l,ae as t,x as s,y as r,D as i,H as n,A as d,F as u,C as m,z as c,I as p,J as f,Y as g,aA as b,q as v,W as _,ax as w,X as h,ay as y,au as k,av as V,aw as C,at as j}from"./index-BSZMSohE.js";import{_ as U}from"./u-input.Cv139uxi.js";import{r as q}from"./uni-app.es.xTxZrAO-.js";import{_ as B}from"./jp-verification-literalness.D2jQUfuc.js";import{_ as P,a as x}from"./u-form.BetUNbkv.js";import{_ as F}from"./u-button.Bc9J1urq.js";import{_ as A}from"./u-code.DKP18sSg.js";import{_ as R}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.rSo6jUIp.js";import"./u-line.Bg8FUxRG.js";import"./u-loading-icon.oQN9K4Zk.js";const z=R({__name:"register",setup(R){const z=e(),$=a(),D=o("mobile"),G=o(),I=o(),T=o(),W=o(),X=o(50),Z=o(),E=o(),H=l({canvasCode:"",mobile:"",email:"",sms_code:"",password:"",confirmPassword:"",invite_code:"",code:"",role:0,from_to:window.location.origin}),J={canvasCode:[{type:"string",required:!0,message:"请输入右侧的图形验证码",trigger:["blur"]},{validator:(e,a)=>a.toLocaleUpperCase()===I.value.identifyCode,message:"图形验证码不正确",trigger:["blur","change"]}],mobile:[{type:"string",required:!0,message:"请输入手机号",trigger:["blur"]},{validator:(e,a)=>uni.$u.test.mobile(a),message:"手机号码不正确",trigger:["blur"]}],email:[{type:"string",required:!0,message:"请输入邮箱地址",trigger:["blur"]},{validator:(e,a)=>uni.$u.test.email(a),message:"邮箱地址不正确",trigger:["blur"]}],sms_code:{type:"string",required:!0,message:"请输入手机验证码",trigger:["blur"]},code:{type:"string",required:!0,message:"请输入邮箱验证码",trigger:["blur"]},password:[{type:"string",required:!0,message:"请输入密码",trigger:["blur"]},{pattern:/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{6,16}$/,message:"密码长度为6-16位，必须包含数字和字母",trigger:["blur","change"]}],confirmPassword:[{type:"string",required:!0,message:"请输入确认密码",trigger:["blur"]},{validator:(e,a)=>a===H.password,message:"两次输入的密码不一致",trigger:["blur","change"]}]};function L(){G.value.validateField("mobile",(async e=>{if(e.length)return void(await v({title:e[0].message,icon:"none"}));if(!Z.value.canGetCode)return void(await v({title:"倒计时结束后再发送",icon:"none"}));await _({title:"正在获取验证码"});const{error:a,message:o}=await w({code:"86",mobile:H.mobile});h(),a?await v({title:o,icon:"none"}):(await v({title:"验证码已发送",icon:"none"}),Z.value.start())}))}function O(){G.value.validateField("email",(async e=>{if(e.length)return void(await v({title:e[0].message,icon:"none"}));if(!E.value.canGetCode)return void(await v({title:"倒计时结束后再发送",icon:"none"}));await _({title:"正在获取验证码"});const{error:a,message:o}=await y({email:H.email,captcha:"spew"});a?await v({title:o,icon:"none"}):(h(),a?await v({title:o,icon:"none"}):(await v({title:"验证码已发送",icon:"none"}),E.value.start()))}))}async function Y(){try{await G.value.validate();const e="mobile"===D.value?k:V,a="mobile"===D.value?{...H,code:"86"}:H,{error:o,message:l,data:t}=await e({...a,form_to:$.availableDomain});if(o)return void(await v({title:l,icon:"none"}));z.setToken(t.token);const s=await C();z.setUserinfo(s.data),v({title:"登录成功！",duration:1e3}).then(),j()}catch(e){e.length&&await v({title:e[0].message,icon:"none"})}}return t(D,(()=>{H.password=H.confirmPassword=""})),(e,a)=>{const o=m,l=q(s("up-input"),U),t=q(s("jp-verification-literalness"),B),v=q(s("up-form-item"),P),_=q(s("up-form"),x),w=q(s("up-button"),F),h=q(s("up-code"),A);return r(),i(u,null,[n(o,{class:"switch"},{default:d((()=>["mobile"===D.value?(r(),c(o,{key:0,class:"font-14-blue",onClick:a[0]||(a[0]=e=>D.value="email")},{default:d((()=>[p("切换到邮箱注册")])),_:1})):(r(),c(o,{key:1,class:"font-14-blue",onClick:a[1]||(a[1]=e=>D.value="mobile")},{default:d((()=>[p("切换到手机号注册")])),_:1}))])),_:1}),n(o,{class:"p-30"},{default:d((()=>[n(_,{class:"form","label-width":"120",labelPosition:"top",model:H,rules:J,ref_key:"formRef",ref:G},{default:d((()=>[n(v,{"left-icon":"account",label:"图形验证码",prop:"canvasCode","border-bottom":""},{right:d((()=>[n(t,{class:"ml-20",ref_key:"canvasRef",ref:I},null,512)])),default:d((()=>[n(l,{modelValue:H.canvasCode,"onUpdate:modelValue":a[2]||(a[2]=e=>H.canvasCode=e),border:"bottom",placeholder:J.canvasCode[0].message},null,8,["modelValue","placeholder"])])),_:1}),"mobile"===D.value?(r(),c(v,{key:0,"left-icon":"account",label:"手机号",prop:"mobile",borderBottom:""},{default:d((()=>[n(l,{modelValue:H.mobile,"onUpdate:modelValue":a[3]||(a[3]=e=>H.mobile=e),placeholder:J.mobile[0].message},null,8,["modelValue","placeholder"])])),_:1})):(r(),c(v,{key:1,"left-icon":"account",label:"邮箱地址",prop:"email",borderBottom:""},{default:d((()=>[n(l,{modelValue:H.email,"onUpdate:modelValue":a[4]||(a[4]=e=>H.email=e),placeholder:J.email[0].message},null,8,["modelValue","placeholder"])])),_:1})),"mobile"===D.value?(r(),c(v,{key:2,"left-icon":"lock",label:"手机验证码",prop:"sms_code",borderBottom:""},{default:d((()=>[n(l,{modelValue:H.sms_code,"onUpdate:modelValue":a[5]||(a[5]=e=>H.sms_code=e),placeholder:J.sms_code.message},{suffix:d((()=>[n(o,{class:"font-12-blue",onClick:L},{default:d((()=>[p(f(T.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1})):(r(),c(v,{key:3,"left-icon":"lock",label:"邮箱验证码",prop:"code",borderBottom:""},{default:d((()=>[n(l,{modelValue:H.code,"onUpdate:modelValue":a[6]||(a[6]=e=>H.code=e),placeholder:J.code.message},{suffix:d((()=>[n(o,{class:"font-12-blue",onClick:O},{default:d((()=>[p(f(W.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1})),n(v,{"left-icon":"lock",label:"密码",prop:"password",borderBottom:""},{default:d((()=>[n(l,{type:"password",modelValue:H.password,"onUpdate:modelValue":a[7]||(a[7]=e=>H.password=e),placeholder:J.password[0].message},null,8,["modelValue","placeholder"])])),_:1}),n(v,{"left-icon":"lock",label:"确认密码",prop:"confirmPassword",borderBottom:""},{default:d((()=>[n(l,{type:"password",modelValue:H.confirmPassword,"onUpdate:modelValue":a[8]||(a[8]=e=>H.confirmPassword=e),placeholder:J.confirmPassword[0].message},null,8,["modelValue","placeholder"])])),_:1}),n(v,{"label-width":"300","left-icon":"lock",label:"推荐人手机号码或邀请码（选填）",prop:"invite_code",borderBottom:""},{default:d((()=>[n(l,{modelValue:H.invite_code,"onUpdate:modelValue":a[9]||(a[9]=e=>H.invite_code=e),placeholder:"请输入推荐人手机号码或邀请码（选填）"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"]),n(w,{class:"mt-30",type:"primary",color:"linear-gradient(to right,#FF4B2B,#FF9C6E)",text:"注册",onClick:Y}),n(h,{seconds:X.value,ref_key:"verificationCodeRef",ref:Z,onChange:a[10]||(a[10]=e=>T.value=e),"unique-key":"mobile"},null,8,["seconds"]),n(h,{seconds:X.value,ref_key:"emailCodeRef",ref:E,onChange:a[11]||(a[11]=e=>W.value=e),"unique-key":"email"},null,8,["seconds"]),n(o,{class:"font-14-blue center-center mt-20",onClick:g(b)},{default:d((()=>[p("已有账户？去登录")])),_:1},8,["onClick"])])),_:1})],64)}}},[["__scopeId","data-v-4ac61274"]]);export{z as default};
