import{R as e,T as a,V as t,U as i,x as o,y as l,D as r,H as s,Y as n,A as m,F as u,C as c,Z as d,I as p,J as f,q as g,W as b,ax as _,X as x,aT as v}from"./index-DXZh4TrB.js";import{_ as w}from"./uni-nav-bar.HncgR8y-.js";import{r as j}from"./uni-app.es.BpJJwZA9.js";import{_ as h}from"./u-input.DdaX0e4J.js";import{_ as y,a as C}from"./u-form.EomHaCjL.js";import{_ as k}from"./u-button.MNc3G8B8.js";import{_ as V}from"./u-code.CBvGKc-9.js";import{_ as $}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CnM6HZsg.js";import"./uni-status-bar.BUxDt1qs.js";import"./u-icon.CtR5oXjZ.js";import"./u-line.DIn8oqBr.js";import"./u-loading-icon.D8mSLZ2D.js";const q=$(Object.assign({name:"user-center-profile-email"},{__name:"user-center-profile-email",setup($){const{t:q}=e(),F=a(),U=t(),B=i({email:F.userinfo.email,code:""}),I=t(),R=t(),D=t(50),H={email:[{type:"string",required:!0,message:q("mail.text.tip1"),trigger:["blur"]},{validator:(e,a)=>uni.$u.test.email(a),message:q("mail.text.tip2"),trigger:["blur","change"]}],code:{type:"string",required:!0,message:q("mail.text.tip3"),trigger:["blur"]}};function J(){U.value.validateField("email",(async e=>{if(e.length)return void(await g({title:e[0].message,icon:"none"}));if(!I.value.canGetCode)return void(await g({title:q("mail.text.tip4"),icon:"none"}));await b({title:q("mail.text.tip5")});const{error:a,message:t}=await _({email:B.email,captcha:"spew"});a?await g({title:t,icon:"none"}):(x(),a?await g({title:t,icon:"none"}):(await g({title:q("mail.text.tip6"),icon:"none"}),I.value.start()))}))}async function L(){try{await U.value.validate();const{error:e,message:a}=await v(B);await g({title:a,icon:"none",duration:1e3}),e||(await F.refreshUserinfo(),d())}catch(e){e.length&&await g({title:e[0].message,icon:"none"})}}return(e,a)=>{const t=j(o("uni-nav-bar"),w),i=j(o("up-input"),h),g=j(o("up-form-item"),y),b=c,_=j(o("up-form"),C),x=j(o("up-button"),k),v=j(o("up-code"),V);return l(),r(u,null,[s(t,{title:e.$t("page.title.mail"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(d)},null,8,["title","onClickLeft"]),s(b,{class:"body"},{default:m((()=>[s(_,{class:"form","label-width":"120",labelPosition:"top",model:B,rules:H,ref_key:"formRef",ref:U},{default:m((()=>[s(g,{required:"","left-icon":"email",label:e.$t("mail.text.yourMailbox"),prop:"email",borderBottom:"",ref:"item1"},{default:m((()=>[s(i,{modelValue:B.email,"onUpdate:modelValue":a[0]||(a[0]=e=>B.email=e),placeholder:H.email[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),s(g,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:"",ref:"item1"},{default:m((()=>[s(i,{type:"password",modelValue:B.code,"onUpdate:modelValue":a[1]||(a[1]=e=>B.code=e),placeholder:H.code.message},{suffix:m((()=>[s(b,{class:"font-12-blue",onClick:J},{default:m((()=>[p(f(R.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),s(x,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:L},null,8,["text"]),s(v,{seconds:D.value,ref_key:"emailCodeRef",ref:I,onChange:a[2]||(a[2]=e=>R.value=e)},null,8,["seconds"])])),_:1})],64)}}}),[["__scopeId","data-v-6919b057"]]);export{q as default};
