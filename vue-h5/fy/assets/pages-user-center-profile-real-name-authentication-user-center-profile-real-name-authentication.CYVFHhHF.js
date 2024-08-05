import{R as e,T as a,U as t,V as l,b8 as i,bN as r,ae as s,x as u,y as d,D as o,H as n,Y as p,A as c,F as m,C as f,Z as _,L as g,E as v,z as h,I as b,J as x,G as y,bO as k,q as w,bP as L,N as C,M as j}from"./index-CxzTsawa.js";import{_ as F}from"./uni-nav-bar.DltHyohR.js";import{r as V}from"./uni-app.es.BDn5iwHj.js";import{_ as $,a as U}from"./u-radio-group.D-gdt-jE.js";import{_ as N}from"./uni-section.QXEit4rW.js";import{_ as R}from"./u-input.CEHarCWZ.js";import{_ as q,a as A}from"./u-form.Dd-Gq3VG.js";import{_ as D}from"./u-upload.D5wWWVMZ.js";import{_ as B}from"./u-button.pIcleLeF.js";import{_ as P}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.C9JthyyK.js";import"./uni-status-bar.CosQ3Tiw.js";import"./u-icon.R77ervPf.js";import"./u-line.C1bQtxUb.js";import"./u-loading-icon.DIVtFoQ4.js";const E=""+new URL("id-card-front-CDseaZ7x.png",import.meta.url).href,O=""+new URL("id-card-back-Dkz4-chN.png",import.meta.url).href,S=""+new URL("passport-CYf6tEWZ.png",import.meta.url).href,Z=P(Object.assign({name:"user-center-profile-real-name-authentication"},{__name:"user-center-profile-real-name-authentication",props:{id_auth:String,id_card:String},setup(P){const{t:Z}=e(),z=P,I=a(),T=t([{label:Z("verified.text.idCardAuthentication"),name:"idCard"},{label:Z("verified.text.passportAuthentication"),name:"passport"}]),W=l(T[0].name),Y=l(),G=l({card_pic_hand:"",card_pic_back:"",passport_pic:"",auth_type:0,name:"",id_card:"",bank_card:"",examine_type:1}),H={name:{type:"string",required:!0,message:Z("verified.text.tip4"),trigger:["blur","change"]},bank_card:[{type:"string",required:!0,message:Z("verified.text.tip5"),trigger:["blur","change"]},{validator:(e,a,t)=>{if(/^[0-9]{16,19}$/.test(a))return!0;t(Z("bankCardRecharge.text.transferCardNumberErrorTip2"))},trigger:["change","blur"]}],id_card:[{type:"string",required:!0,message:Z("verified.text.tip6"),trigger:["blur","change"]},{validator:(e,a)=>"idCard"===W.value?i.idCard(a):r(a),message:Z("verified.text.tip7"),trigger:["blur","change"]}],card_pic_hand:{type:"string",required:!0,message:Z("verified.text.tip8"),trigger:["blur","change"]},card_pic_back:{type:"string",required:!0,message:Z("verified.text.tip9"),trigger:["blur","change"]},passport_pic:{type:"string",required:!0,message:Z("verified.text.tip10"),trigger:["blur","change"]}},J=l([]),M=l([]),X=l([]);let K=l("");async function Q(e,a){const t="passportFileList"===a?J:"frontFileList"===a?M:X,l="passportFileList"===a?"passport_pic":"frontFileList"===a?"card_pic_hand":"card_pic_back";t.value=[{...e.file,status:"uploading",message:Z("verified.text.uploading")}];const i=await k(e.file.url);1===Number(i.status)?(t.value=[{...e.file,status:"success",message:Z("verified.text.uploadSuccessFully")}],G.value[l]=i.data.file_path):await w({title:i.message||Z("verified.text.uploadFailed"),icon:"none"})}async function ee(){try{if(0===G.value.auth_type&&"0"===z.id_auth&&await Y.value.validate(),0===G.value.auth_type&&"2"===z.id_auth&&""===G.value.name)return await w({title:"请填写真实姓名!",icon:"none"}),!1;if(0===G.value.auth_type&&"2"===z.id_auth&&0===M.value.length||0===G.value.auth_type&&"2"===z.id_auth&&0===X.value.length)return await w({title:"请上传完整的身份证!",icon:"none"}),!1;if(1===G.value.auth_type&&0===J.value.length)return await w({title:"请上传或护照！",icon:"none"}),!1;const{error:e,message:a}=await L(G.value);await w({title:a,icon:"none"}),e||(await I.refreshUserinfo(),_())}catch(e){e.length&&await w({title:e[0].message,icon:"none"})}}s(W,(e=>{G.value.auth_type="idCard"===e?0:1,G.value.id_card=""}));return z.id_card&&"0"===z.id_auth?K.value="实名中":z.id_card||"0"!==z.id_auth?"2"===z.id_auth?(K.value="重新申请",G.value.examine_type=2):K.value="提交申请":(K.value="去认证",G.value.examine_type=1),(e,a)=>{const t=V(u("uni-nav-bar"),F),l=V(u("up-radio"),$),i=V(u("up-radio-group"),U),r=V(u("uni-section"),N),s=f,k=V(u("up-input"),R),w=V(u("up-form-item"),q),L=V(u("up-form"),A),P=C,Z=V(u("up-upload"),D),I=V(u("up-button"),B);return d(),o(m,null,[n(t,{title:e.$t("page.title.verified"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:p(_)},null,8,["title","onClickLeft"]),n(s,{class:"body"},{default:c((()=>[g(n(r,{title:e.$t("verified.text.title1"),type:"line"},{default:c((()=>[n(i,{class:"radio-group",modelValue:W.value,"onUpdate:modelValue":a[0]||(a[0]=e=>W.value=e),placement:"row","icon-placement":"right"},{default:c((()=>[(d(!0),o(m,null,v(T,((e,a)=>(d(),h(l,{style:{flex:"1","justify-content":"center"},key:a,label:e.label,name:e.name},null,8,["label","name"])))),128))])),_:1},8,["modelValue"])])),_:1},8,["title"]),[[j,!1]]),0===G.value.auth_type&&"2"!==z.id_auth?(d(),h(r,{key:0,title:e.$t("verified.text.title2"),type:"line"},{default:c((()=>[n(s,{class:"tip-text"},{default:c((()=>[b(x(e.$t("verified.text.tip1")),1)])),_:1}),n(L,{class:"plr-30","label-width":"120",labelPosition:"top",model:G.value,rules:H,ref_key:"formRef",ref:Y},{default:c((()=>[n(w,{"left-icon":"account",label:e.$t("verified.text.actualName"),prop:"name",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{modelValue:G.value.name,"onUpdate:modelValue":a[1]||(a[1]=e=>G.value.name=e),placeholder:H.name.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),n(w,{"left-icon":"lock",label:e.$t("verified.text.idNumber"),prop:"id_card",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{type:"idcard",modelValue:G.value.id_card,"onUpdate:modelValue":a[2]||(a[2]=e=>G.value.id_card=e),placeholder:H.id_card[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),n(w,{"left-icon":"lock",label:e.$t("verified.text.bankCardNumber"),prop:"bank_card",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{type:"idcard",modelValue:G.value.bank_card,"onUpdate:modelValue":a[3]||(a[3]=e=>G.value.bank_card=e),placeholder:H.bank_card[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"])])),_:1},8,["title"])):y("",!0),"idCard"===W.value&&"2"===z.id_auth?(d(),h(r,{key:1,title:"请上传身份证的正反面",type:"line",padding:"30rpx"},{default:c((()=>[n(L,{"label-width":"120",labelPosition:"top",model:G.value},{default:c((()=>[n(w,{"left-icon":"account",label:e.$t("verified.text.actualName"),prop:"name",borderBottom:"",ref:"item1"},{default:c((()=>[n(k,{modelValue:G.value.name,"onUpdate:modelValue":a[4]||(a[4]=e=>G.value.name=e),placeholder:H.name.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),n(s,{class:"uploads"},{default:c((()=>[n(Z,{class:"uploads-item",width:"750rpx",fileList:M.value,onAfterRead:a[5]||(a[5]=e=>Q(e,"frontFileList")),onDelete:a[6]||(a[6]=e=>M.value=[]),name:"url",maxCount:1},{default:c((()=>[n(s,{class:"uploads-item-content"},{default:c((()=>[n(s,{class:"uploads-item__info"},{default:c((()=>[n(s,{class:"font"},{default:c((()=>[b("头像面")])),_:1}),n(s,{class:"font-12 mt-10"},{default:c((()=>[b("上传你的身份证头像面")])),_:1})])),_:1}),n(P,{class:"uploads-item__image",src:E,mode:"widthFix"})])),_:1})])),_:1},8,["fileList"]),n(Z,{class:"uploads-item mt-20",width:"750rpx",fileList:X.value,onAfterRead:a[7]||(a[7]=e=>Q(e,"backFileList")),onDelete:a[8]||(a[8]=e=>X.value=[]),name:"url",maxCount:1},{default:c((()=>[n(s,{class:"uploads-item-content"},{default:c((()=>[n(s,{class:"uploads-item__info"},{default:c((()=>[n(s,{class:"font-14"},{default:c((()=>[b("国徽面")])),_:1}),n(s,{class:"font-12 mt-10"},{default:c((()=>[b("上传你的身份证国徽面")])),_:1})])),_:1}),n(P,{class:"uploads-item__image",src:O,mode:"widthFix"})])),_:1})])),_:1},8,["fileList"])])),_:1})])),_:1})):y("",!0),1===G.value.auth_type?(d(),h(r,{key:2,title:e.$t("verified.text.tip10"),type:"line",padding:"30rpx"},{default:c((()=>[n(s,{class:"passport-wrapper"},{default:c((()=>[n(s,{class:"font-24-000"},{default:c((()=>[b(x(e.$t("verified.text.tip2")),1)])),_:1}),n(s,{class:"font-12"},{default:c((()=>[b(x(e.$t("verified.text.tip3")),1)])),_:1}),n(Z,{width:"564rpx",height:"368rpx",class:"passport-content mt-50",fileList:J.value,onAfterRead:a[9]||(a[9]=e=>Q(e,"passportFileList")),onDelete:a[10]||(a[10]=e=>J.value=[]),name:"url",maxCount:1},{default:c((()=>[n(P,{class:"passport-image",src:S,mode:"widthFix"})])),_:1},8,["fileList"])])),_:1})])),_:1},8,["title"])):y("",!0),n(s,{class:"mt-100 p-30"},{default:c((()=>[n(I,{text:p(K),type:"primary",onClick:ee},null,8,["text"])])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-871613d4"]]);export{Z as default};
