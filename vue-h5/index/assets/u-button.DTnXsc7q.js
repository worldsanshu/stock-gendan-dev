import{_ as e}from"./u-loading-icon.Dw3VzJVZ.js";import{a1 as t,a2 as o,a3 as a,be as i,a5 as n,bf as r,x as s,y as l,z as p,A as u,D as d,F as g,H as h,aa as m,I as c,J as b,G as y,a9 as f,B as S,O as v,bg as z}from"./index-CKrpdSUd.js";import{r as x}from"./uni-app.es.DtZw7P7S.js";import{_ as T}from"./u-icon.Ci5AyJSD.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";const M=C({name:"u-button",mixins:[o,a,{props:{hairline:{type:Boolean,default:()=>t.button.hairline},type:{type:String,default:()=>t.button.type},size:{type:String,default:()=>t.button.size},shape:{type:String,default:()=>t.button.shape},plain:{type:Boolean,default:()=>t.button.plain},disabled:{type:Boolean,default:()=>t.button.disabled},loading:{type:Boolean,default:()=>t.button.loading},loadingText:{type:[String,Number],default:()=>t.button.loadingText},loadingMode:{type:String,default:()=>t.button.loadingMode},loadingSize:{type:[String,Number],default:()=>t.button.loadingSize},openType:{type:String,default:()=>t.button.openType},formType:{type:String,default:()=>t.button.formType},appParameter:{type:String,default:()=>t.button.appParameter},hoverStopPropagation:{type:Boolean,default:()=>t.button.hoverStopPropagation},lang:{type:String,default:()=>t.button.lang},sessionFrom:{type:String,default:()=>t.button.sessionFrom},sendMessageTitle:{type:String,default:()=>t.button.sendMessageTitle},sendMessagePath:{type:String,default:()=>t.button.sendMessagePath},sendMessageImg:{type:String,default:()=>t.button.sendMessageImg},showMessageCard:{type:Boolean,default:()=>t.button.showMessageCard},dataName:{type:String,default:()=>t.button.dataName},throttleTime:{type:[String,Number],default:()=>t.button.throttleTime},hoverStartTime:{type:[String,Number],default:()=>t.button.hoverStartTime},hoverStayTime:{type:[String,Number],default:()=>t.button.hoverStayTime},text:{type:[String,Number],default:()=>t.button.text},icon:{type:String,default:()=>t.button.icon},iconColor:{type:String,default:()=>t.button.icon},color:{type:String,default:()=>t.button.color}}}],data:()=>({}),computed:{bemClass(){return this.color?this.bem("button",["shape","size"],["disabled","plain","hairline"]):this.bem("button",["type","shape","size"],["disabled","plain","hairline"])},loadingColor(){return this.plain?this.color?this.color:i[`u-${this.type}`]:"info"===this.type?"#c9c9c9":"rgb(200, 200, 200)"},iconColorCom(){return this.iconColor?this.iconColor:this.plain?this.color?this.color:this.type:"info"===this.type?"#000000":"#ffffff"},baseColor(){let e={};return this.color&&(e.color=this.plain?this.color:"white",this.plain||(e["background-color"]=this.color),-1!==this.color.indexOf("gradient")?(e.borderTopWidth=0,e.borderRightWidth=0,e.borderBottomWidth=0,e.borderLeftWidth=0,this.plain||(e.backgroundImage=this.color)):(e.borderColor=this.color,e.borderWidth="1px",e.borderStyle="solid")),e},nvueTextStyle(){let e={};return"info"===this.type&&(e.color="#323233"),this.color&&(e.color=this.plain?this.color:"white"),e.fontSize=this.textSize+"px",e},textSize(){let e=14,{size:t}=this;return"large"===t&&(e=16),"normal"===t&&(e=14),"small"===t&&(e=12),"mini"===t&&(e=10),e}},emits:["click","getphonenumber","getuserinfo","error","opensetting","launchapp","agreeprivacyauthorization"],methods:{addStyle:n,clickHandler(){this.disabled||this.loading||r((()=>{this.$emit("click")}),this.throttleTime)},getphonenumber(e){this.$emit("getphonenumber",e)},getuserinfo(e){this.$emit("getuserinfo",e)},error(e){this.$emit("error",e)},opensetting(e){this.$emit("opensetting",e)},launchapp(e){this.$emit("launchapp",e)},agreeprivacyauthorization(e){this.$emit("agreeprivacyauthorization",e)}}},[["render",function(t,o,a,i,n,r){const C=x(s("u-loading-icon"),e),M=v,_=x(s("u-icon"),T),N=z;return l(),p(N,{"hover-start-time":Number(t.hoverStartTime),"hover-stay-time":Number(t.hoverStayTime),"form-type":t.formType,"open-type":t.openType,"app-parameter":t.appParameter,"hover-stop-propagation":t.hoverStopPropagation,"send-message-title":t.sendMessageTitle,"send-message-path":t.sendMessagePath,lang:t.lang,"data-name":t.dataName,"session-from":t.sessionFrom,"send-message-img":t.sendMessageImg,"show-message-card":t.showMessageCard,onGetphonenumber:r.getphonenumber,onGetuserinfo:r.getuserinfo,onError:r.error,onOpensetting:r.opensetting,onLaunchapp:r.launchapp,onAgreeprivacyauthorization:r.agreeprivacyauthorization,"hover-class":t.disabled||t.loading?"":"u-button--active",class:S(["u-button u-reset-button",r.bemClass]),style:m([r.baseColor,r.addStyle(t.customStyle)]),onClick:r.clickHandler},{default:u((()=>[t.loading?(l(),d(g,{key:0},[h(C,{mode:t.loadingMode,size:1.15*t.loadingSize,color:r.loadingColor},null,8,["mode","size","color"]),h(M,{class:"u-button__loading-text",style:m([{fontSize:r.textSize+"px"}])},{default:u((()=>[c(b(t.loadingText||t.text),1)])),_:1},8,["style"])],64)):(l(),d(g,{key:1},[t.icon?(l(),p(_,{key:0,name:t.icon,color:r.iconColorCom,size:1.35*r.textSize,customStyle:{marginRight:"2px"}},null,8,["name","color","size"])):y("",!0),f(t.$slots,"default",{},(()=>[h(M,{class:"u-button__text",style:m([{fontSize:r.textSize+"px"}])},{default:u((()=>[c(b(t.text),1)])),_:1},8,["style"])]),!0)],64))])),_:3},8,["hover-start-time","hover-stay-time","form-type","open-type","app-parameter","hover-stop-propagation","send-message-title","send-message-path","lang","data-name","session-from","send-message-img","show-message-card","onGetphonenumber","onGetuserinfo","onError","onOpensetting","onLaunchapp","onAgreeprivacyauthorization","hover-class","style","onClick","class"])}],["__scopeId","data-v-c4ed8711"]]);export{M as _};
