import{R as e,T as t,V as o,U as a,x as i,y as l,D as r,H as s,Y as n,A as u,F as m,C as c,Z as d,I as p,J as f,q as b,W as g,aw as _,X as v,aZ as w}from"./index-DXZh4TrB.js";import{_ as j}from"./uni-nav-bar.HncgR8y-.js";import{r as x}from"./uni-app.es.BpJJwZA9.js";import{_ as h}from"./u-input.DdaX0e4J.js";import{_ as y,a as C}from"./u-form.EomHaCjL.js";import{_ as k}from"./u-button.MNc3G8B8.js";import{_ as V}from"./u-code.CBvGKc-9.js";import{_ as q}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CnM6HZsg.js";import"./uni-status-bar.BUxDt1qs.js";import"./u-icon.CtR5oXjZ.js";import"./u-line.DIn8oqBr.js";import"./u-loading-icon.D8mSLZ2D.js";const F=q(Object.assign({name:"user-center-profile-email"},{__name:"user-center-modify-mobile-phone",setup(q){const{t:F}=e(),$=t(),B=o(),I=a({mobile:"",captcha:""}),R=o(),U=o(),Z=o(50),D={mobile:[{type:"string",required:!0,message:"请输入要修改的手机号",trigger:["blur"]},{validator:(e,t)=>uni.$u.test.mobile(t),message:"手机号不正确",trigger:["blur","change"]}],code:{type:"string",required:!0,message:F("mail.text.tip3"),trigger:["blur"]}};function H(){B.value.validateField("mobile",(async e=>{if(e.length)return void(await b({title:e[0].message,icon:"none"}));if(!R.value.canGetCode)return void(await b({title:F("mail.text.tip4"),icon:"none"}));await g({title:F("mail.text.tip5")});const{error:t,message:o}=await _({mobile:I.mobile,code:"86"});t?await b({title:o,icon:"none"}):(v(),t?await b({title:o,icon:"none"}):(await b({title:F("mail.text.tip6"),icon:"none"}),R.value.start()))}))}async function J(){try{await B.value.validate();const{error:e,message:t}=await w(I);0===e?(await b({title:"修改成功，请重新登录!",icon:"none",duration:1500}),setTimeout((()=>{$.logout()}),1500)):await b({title:t,icon:"none"})}catch(e){e.length&&await b({title:e[0].message,icon:"none"})}}return(e,t)=>{const o=x(i("uni-nav-bar"),j),a=x(i("up-input"),h),b=x(i("up-form-item"),y),g=c,_=x(i("up-form"),C),v=x(i("up-button"),k),w=x(i("up-code"),V);return l(),r(m,null,[s(o,{title:"修改手机","status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(d)},null,8,["onClickLeft"]),s(g,{class:"body"},{default:u((()=>[s(_,{class:"form","label-width":"120",labelPosition:"top",model:I,rules:D,ref_key:"formRef",ref:B},{default:u((()=>[s(b,{required:"","left-icon":"phone",label:"手机号",prop:"mobile",borderBottom:"",ref:"item1"},{default:u((()=>[s(a,{modelValue:I.mobile,"onUpdate:modelValue":t[0]||(t[0]=e=>I.mobile=e),placeholder:"请输入要修改的手机号"},null,8,["modelValue"])])),_:1},512),s(b,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:"",ref:"item1"},{default:u((()=>[s(a,{type:"password",modelValue:I.code,"onUpdate:modelValue":t[1]||(t[1]=e=>I.code=e),placeholder:D.code.message},{suffix:u((()=>[s(g,{class:"font-12-blue",onClick:H},{default:u((()=>[p(f(U.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),s(v,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:J},null,8,["text"]),s(w,{seconds:Z.value,ref_key:"mobileCodeRef",ref:R,onChange:t[2]||(t[2]=e=>U.value=e)},null,8,["seconds"])])),_:1})],64)}}}),[["__scopeId","data-v-5a75ce95"]]);export{F as default};
