import{R as e,T as a,U as t,V as l,b8 as i,bN as r,ae as s,x as u,y as d,D as o,H as n,Y as p,A as c,F as m,C as f,Z as _,L as v,E as g,z as h,I as b,J as x,G as y,bO as k,q as w,bP as L,N as j,M as V}from"./index-BKBmZM5g.js";import{_ as C}from"./uni-nav-bar.DQSGXMB-.js";import{r as F}from"./uni-app.es.CT9bmSuD.js";import{_ as $,a as U}from"./u-radio-group.Ctvm11DA.js";import{_ as N}from"./uni-section.PH4E6Z4V.js";import{_ as q}from"./u-input.Dg8GBcSs.js";import{_ as R,a as A}from"./u-form.C9P8YSaz.js";import{_ as D}from"./u-upload.BYL3aJQu.js";import{_ as B}from"./u-button.CPApju8R.js";import{_ as Z}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.o26QNkQn.js";import"./uni-status-bar.CrgiwTPU.js";import"./u-icon.ChK8Jwfk.js";import"./u-line.DE4nbZs9.js";import"./u-loading-icon.BDLNhgIp.js";const O=""+new URL("id-card-front-CDseaZ7x.png",import.meta.url).href,P=""+new URL("id-card-back-Dkz4-chN.png",import.meta.url).href,S=""+new URL("passport-CYf6tEWZ.png",import.meta.url).href,z=Z(Object.assign({name:"user-center-profile-real-name-authentication"},{__name:"user-center-profile-real-name-authentication",props:{id_auth:String,id_card:String},setup(Z){const{t:z}=e(),E=Z,I=a(),T=t([{label:z("verified.text.idCardAuthentication"),name:"idCard"},{label:z("verified.text.passportAuthentication"),name:"passport"}]),W=l(T[0].name),Y=l(),G=l({card_pic_hand:"",card_pic_back:"",passport_pic:"",auth_type:0,name:"",id_card:"",bank_card:"",examine_type:1}),H={name:{type:"string",required:!0,message:z("verified.text.tip4"),trigger:["blur","change"]},bank_card:{type:"string",required:!0,message:z("verified.text.tip5"),trigger:["blur","change"]},id_card:[{type:"string",required:!0,message:z("verified.text.tip6"),trigger:["blur","change"]},{validator:(e,a)=>"idCard"===W.value?i.idCard(a):r(a),message:z("verified.text.tip7"),trigger:["blur","change"]}],card_pic_hand:{type:"string",required:!0,message:z("verified.text.tip8"),trigger:["blur","change"]},card_pic_back:{type:"string",required:!0,message:z("verified.text.tip9"),trigger:["blur","change"]},passport_pic:{type:"string",required:!0,message:z("verified.text.tip10"),trigger:["blur","change"]}},J=l([]),M=l([]),K=l([]);let Q=l("");async function X(e,a){const t="passportFileList"===a?J:"frontFileList"===a?M:K,l="passportFileList"===a?"passport_pic":"frontFileList"===a?"card_pic_hand":"card_pic_back";t.value=[{...e.file,status:"uploading",message:z("verified.text.uploading")}];const i=await k(e.file.url);1===Number(i.status)?(t.value=[{...e.file,status:"success",message:z("verified.text.uploadSuccessFully")}],G.value[l]=i.data.file_path):await w({title:i.message||z("verified.text.uploadFailed"),icon:"none"})}async function ee(){try{if(0===G.value.auth_type&&"0"===E.id_auth&&await Y.value.validate(),0===G.value.auth_type&&"2"===E.id_auth&&""===G.value.name)return await w({title:"请填写真实姓名!",icon:"none"}),!1;if(0===G.value.auth_type&&"2"===E.id_auth&&0===M.value.length||0===G.value.auth_type&&"2"===E.id_auth&&0===K.value.length)return await w({title:"请上传完整的身份证!",icon:"none"}),!1;if(1===G.value.auth_type&&0===J.value.length)return await w({title:"请上传或护照！",icon:"none"}),!1;const{error:e,message:a}=await L(G.value);await w({title:a,icon:"none"}),e||(await I.refreshUserinfo(),_())}catch(e){e.length&&await w({title:e[0].message,icon:"none"})}}s(W,(e=>{G.value.auth_type="idCard"===e?0:1,G.value.id_card=""}));return E.id_card&&"0"===E.id_auth?Q.value="实名中":E.id_card||"0"!==E.id_auth?"2"===E.id_auth?(Q.value="重新申请",G.value.examine_type=2):Q.value="提交申请":(Q.value="去认证",G.value.examine_type=1),(e,a)=>{const t=F(u("uni-nav-bar"),C),l=F(u("up-radio"),$),i=F(u("up-radio-group"),U),r=F(u("uni-section"),N),s=f,k=F(u("up-input"),q),w=F(u("up-form-item"),R),L=F(u("up-form"),A),Z=j,z=F(u("up-upload"),D),I=F(u("up-button"),B);return d(),o(m,null,[n(t,{title:e.$t("page.title.verified"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:p(_)},null,8,["title","onClickLeft"]),n(s,{class:"body"},{default:c((()=>[v(n(r,{title:e.$t("verified.text.title1"),type:"line"},{default:c((()=>[n(i,{class:"radio-group",modelValue:W.value,"onUpdate:modelValue":a[0]||(a[0]=e=>W.value=e),placement:"row","icon-placement":"right"},{default:c((()=>[(d(!0),o(m,null,g(T,((e,a)=>(d(),h(l,{style:{flex:"1","justify-content":"center"},key:a,label:e.label,name:e.name},null,8,["label","name"])))),128))])),_:1},8,["modelValue"])])),_:1},8,["title"]),[[V,!1]]),0===G.value.auth_type&&"2"!==E.id_auth?(d(),h(r,{key:0,title:e.$t("verified.text.title2"),type:"line"},{default:c((()=>[n(s,{class:"tip-text"},{default:c((()=>[b(x(e.$t("verified.text.tip1")),1)])),_:1}),n(L,{class:"plr-30","label-width":"120",labelPosition:"top",model:G.value,rules:H,ref_key:"formRef",ref:Y},{default:c((()=>[n(w,{"left-icon":"account",label:e.$t("verified.text.actualName"),prop:"name",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{modelValue:G.value.name,"onUpdate:modelValue":a[1]||(a[1]=e=>G.value.name=e),placeholder:H.name.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),n(w,{"left-icon":"lock",label:e.$t("verified.text.idNumber"),prop:"id_card",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{type:"idcard",modelValue:G.value.id_card,"onUpdate:modelValue":a[2]||(a[2]=e=>G.value.id_card=e),placeholder:H.id_card[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),n(w,{"left-icon":"lock",label:e.$t("verified.text.bankCardNumber"),prop:"bank_card",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{type:"idcard",modelValue:G.value.bank_card,"onUpdate:modelValue":a[3]||(a[3]=e=>G.value.bank_card=e),placeholder:H.bank_card.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"])])),_:1},8,["title"])):y("",!0),"idCard"===W.value&&"2"===E.id_auth?(d(),h(r,{key:1,title:"请上传身份证的正反面",type:"line",padding:"30rpx"},{default:c((()=>[n(L,{"label-width":"120",labelPosition:"top",model:G.value},{default:c((()=>[n(w,{"left-icon":"account",label:e.$t("verified.text.actualName"),prop:"name",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{modelValue:G.value.name,"onUpdate:modelValue":a[4]||(a[4]=e=>G.value.name=e),placeholder:H.name.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),n(s,{class:"uploads"},{default:c((()=>[n(z,{class:"uploads-item",width:"750rpx",fileList:M.value,onAfterRead:a[5]||(a[5]=e=>X(e,"frontFileList")),onDelete:a[6]||(a[6]=e=>M.value=[]),name:"url",maxCount:1},{default:c((()=>[n(s,{class:"uploads-item-content"},{default:c((()=>[n(s,{class:"uploads-item__info"},{default:c((()=>[n(s,{class:"font"},{default:c((()=>[b("头像面")])),_:1}),n(s,{class:"font-12 mt-10"},{default:c((()=>[b("上传你的身份证头像面")])),_:1})])),_:1}),n(Z,{class:"uploads-item__image",src:O,mode:"widthFix"})])),_:1})])),_:1},8,["fileList"]),n(z,{class:"uploads-item mt-20",width:"750rpx",fileList:K.value,onAfterRead:a[7]||(a[7]=e=>X(e,"backFileList")),onDelete:a[8]||(a[8]=e=>K.value=[]),name:"url",maxCount:1},{default:c((()=>[n(s,{class:"uploads-item-content"},{default:c((()=>[n(s,{class:"uploads-item__info"},{default:c((()=>[n(s,{class:"font-14"},{default:c((()=>[b("国徽面")])),_:1}),n(s,{class:"font-12 mt-10"},{default:c((()=>[b("上传你的身份证国徽面")])),_:1})])),_:1}),n(Z,{class:"uploads-item__image",src:P,mode:"widthFix"})])),_:1})])),_:1},8,["fileList"])])),_:1})])),_:1})):y("",!0),1===G.value.auth_type?(d(),h(r,{key:2,title:e.$t("verified.text.tip10"),type:"line",padding:"30rpx"},{default:c((()=>[n(s,{class:"passport-wrapper"},{default:c((()=>[n(s,{class:"font-24-000"},{default:c((()=>[b(x(e.$t("verified.text.tip2")),1)])),_:1}),n(s,{class:"font-12"},{default:c((()=>[b(x(e.$t("verified.text.tip3")),1)])),_:1}),n(z,{width:"564rpx",height:"368rpx",class:"passport-content mt-50",fileList:J.value,onAfterRead:a[9]||(a[9]=e=>X(e,"passportFileList")),onDelete:a[10]||(a[10]=e=>J.value=[]),name:"url",maxCount:1},{default:c((()=>[n(Z,{class:"passport-image",src:S,mode:"widthFix"})])),_:1},8,["fileList"])])),_:1})])),_:1},8,["title"])):y("",!0),n(s,{class:"mt-100 p-30"},{default:c((()=>[n(I,{text:p(Q),type:"primary",onClick:ee},null,8,["text"])])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-3d8d5d85"]]);export{z as default};
