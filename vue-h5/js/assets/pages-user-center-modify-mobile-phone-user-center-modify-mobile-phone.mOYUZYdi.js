import{R as e,T as t,V as o,U as a,x as i,y as l,D as r,H as s,Y as n,A as u,F as m,C as c,Z as d,I as p,J as f,q as b,W as g,ax as _,X as v,bM as x}from"./index-kFUeJ8hV.js";import{_ as j}from"./uni-nav-bar.0SfVGGBu.js";import{r as w}from"./uni-app.es.I3XY_Qp0.js";import{_ as h}from"./u-input.1vvxhsyA.js";import{_ as y,a as C}from"./u-form.CM2FTP_d.js";import{_ as k}from"./u-button.BBVOiPxg.js";import{_ as V}from"./u-code.CVaUfUgO.js";import{_ as q}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Co6_VDA4.js";import"./uni-status-bar.B5r9ago2.js";import"./u-icon.Cigz3oKI.js";import"./u-line.CnsYa4qO.js";import"./u-loading-icon.k-zjl7PC.js";const F=q(Object.assign({name:"user-center-profile-email"},{__name:"user-center-modify-mobile-phone",setup(q){const{t:F}=e(),$=t(),R=o(),T=a({mobile:"",captcha:""}),U=o(),B=o(),I=o(50),L={mobile:[{type:"string",required:!0,message:"请输入要修改的手机号",trigger:["blur"]},{validator:(e,t)=>uni.$u.test.mobile(t),message:"手机号不正确",trigger:["blur","change"]}],code:{type:"string",required:!0,message:F("mail.text.tip3"),trigger:["blur"]}};function O(){R.value.validateField("mobile",(async e=>{if(e.length)return void(await b({title:e[0].message,icon:"none"}));if(!U.value.canGetCode)return void(await b({title:F("mail.text.tip4"),icon:"none"}));await g({title:F("mail.text.tip5")});const{error:t,message:o}=await _({mobile:T.mobile,code:"86"});t?await b({title:o,icon:"none"}):(v(),t?await b({title:o,icon:"none"}):(await b({title:F("mail.text.tip6"),icon:"none"}),U.value.start()))}))}async function W(){try{await R.value.validate();const{error:e,message:t}=await x(T);0===e?(await b({title:"修改成功，请重新登录!",icon:"none",duration:1500}),setTimeout((()=>{$.logout()}),1500)):await b({title:t,icon:"none"})}catch(e){e.length&&await b({title:e[0].message,icon:"none"})}}return(e,t)=>{const o=w(i("uni-nav-bar"),j),a=w(i("up-input"),h),b=w(i("up-form-item"),y),g=c,_=w(i("up-form"),C),v=w(i("up-button"),k),x=w(i("up-code"),V);return l(),r(m,null,[s(o,{title:"修改手机","status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(d)},null,8,["onClickLeft"]),s(g,{class:"body"},{default:u((()=>[s(_,{class:"form","label-width":"120",labelPosition:"top",model:T,rules:L,ref_key:"formRef",ref:R},{default:u((()=>[s(b,{required:"","left-icon":"phone",label:"手机号",prop:"mobile",borderBottom:"",ref:"item1"},{default:u((()=>[s(a,{modelValue:T.mobile,"onUpdate:modelValue":t[0]||(t[0]=e=>T.mobile=e),placeholder:"请输入要修改的手机号"},null,8,["modelValue"])])),_:1},512),s(b,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:"",ref:"item1"},{default:u((()=>[s(a,{type:"password",modelValue:T.code,"onUpdate:modelValue":t[1]||(t[1]=e=>T.code=e),placeholder:L.code.message},{suffix:u((()=>[s(g,{class:"font-12-blue",onClick:O},{default:u((()=>[p(f(B.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),s(v,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:W},null,8,["text"]),s(x,{seconds:I.value,ref_key:"mobileCodeRef",ref:U,onChange:t[2]||(t[2]=e=>B.value=e)},null,8,["seconds"])])),_:1})],64)}}}),[["__scopeId","data-v-5a75ce95"]]);export{F as default};
