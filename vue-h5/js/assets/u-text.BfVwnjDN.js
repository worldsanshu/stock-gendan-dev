import{a1 as e,b8 as t,bt as s,bu as n,bv as a,a2 as l,a3 as o,al as i,a4 as r,a5 as u,bw as p,x as d,y as c,z as f,A as y,B as m,aa as g,I as x,G as h,H as S,J as b,O as _,C as v,bg as k}from"./index-DKUjMFTs.js";import{_ as w}from"./u-icon.DqEvg06s.js";import{r as N}from"./uni-app.es.UMZ84mjS.js";import{_ as I}from"./u-link.C3IL6S13.js";import{_ as W}from"./_plugin-vue_export-helper.BCo6x5W8.js";const j=W({name:"u--text",mixins:[l,o,{computed:{value(){const{text:e,mode:l,format:o,href:i}=this;return"price"===l?t.func(o)?o(e):s(e,2):"date"===l?(!t.date(e)&&n(),t.func(o)?o(e):a(e,o||"yyyy-mm-dd")):"phone"===l?t.func(o)?o(e):"encrypt"===o?`${e.substr(0,3)}****${e.substr(7)}`:e:"name"===l?t.func(o)?o(e):"encrypt"===o?this.formatName(e):e:"link"===l?(!t.url(i)&&n(),e):e}},methods:{formatName(e){let t="";if(2===e.length)t=e.substr(0,1)+"*";else if(e.length>2){let s="";for(let t=0,n=e.length-2;t<n;t++)s+="*";t=e.substr(0,1)+s+e.substr(-1,1)}else t=e;return t}}},{props:{type:{type:String,default:()=>e.text.type},show:{type:Boolean,default:()=>e.text.show},text:{type:[String,Number],default:()=>e.text.text},prefixIcon:{type:String,default:()=>e.text.prefixIcon},suffixIcon:{type:String,default:()=>e.text.suffixIcon},mode:{type:String,default:()=>e.text.mode},href:{type:String,default:()=>e.text.href},format:{type:[String,Function],default:()=>e.text.format},call:{type:Boolean,default:()=>e.text.call},openType:{type:String,default:()=>e.text.openType},bold:{type:Boolean,default:()=>e.text.bold},block:{type:Boolean,default:()=>e.text.block},lines:{type:[String,Number],default:()=>e.text.lines},color:{type:String,default:()=>e.text.color},size:{type:[String,Number],default:()=>e.text.size},iconStyle:{type:[Object,String],default:()=>e.text.iconStyle},decoration:{tepe:String,default:()=>e.text.decoration},margin:{type:[Object,String,Number],default:()=>e.text.margin},lineHeight:{type:[String,Number],default:()=>e.text.lineHeight},align:{type:String,default:()=>e.text.align},wordWrap:{type:String,default:()=>e.text.wordWrap}}}],emits:["click"],computed:{valueStyle(){const e={textDecoration:this.decoration,fontWeight:this.bold?"bold":"normal",wordWrap:this.wordWrap,fontSize:i(this.size)};return!this.type&&(e.color=this.color),this.isNvue&&this.lines&&(e.lines=this.lines),this.lineHeight&&(e.lineHeight=i(this.lineHeight)),!this.isNvue&&this.block&&(e.display="block"),r(e,u(this.customStyle))},isNvue:()=>!1,isMp:()=>!1},data:()=>({}),methods:{addStyle:u,clickHandler(){this.call&&"phone"===this.mode&&p({phoneNumber:this.text}),this.$emit("click")}}},[["render",function(e,t,s,n,a,l){const o=_,i=N(d("u-icon"),w),r=v,u=N(d("u-link"),I),p=k;return e.show?(c(),f(r,{key:0,class:m(["u-text",[]]),style:g({margin:e.margin,justifyContent:"left"===e.align?"flex-start":"center"===e.align?"center":"flex-end"}),onClick:l.clickHandler},{default:y((()=>["price"===e.mode?(c(),f(o,{key:0,class:m(["u-text__price",e.type&&`u-text__value--${e.type}`]),style:g([l.valueStyle])},{default:y((()=>[x("￥")])),_:1},8,["class","style"])):h("",!0),e.prefixIcon?(c(),f(r,{key:1,class:"u-text__prefix-icon"},{default:y((()=>[S(i,{name:e.prefixIcon,customStyle:l.addStyle(e.iconStyle)},null,8,["name","customStyle"])])),_:1})):h("",!0),"link"===e.mode?(c(),f(u,{key:2,class:m(["u-text__value",[e.type&&`u-text__value--${e.type}`,e.lines&&`u-line-${e.lines}`]]),style:g({fontWeight:l.valueStyle.fontWeight,wordWrap:l.valueStyle.wordWrap,fontSize:l.valueStyle.fontSize}),text:e.value,href:e.href,underLine:""},null,8,["style","class","text","href"])):e.openType&&l.isMp?(c(),f(p,{key:3,class:"u-reset-button u-text__value",style:g([l.valueStyle]),"data-index":e.index,openType:e.openType,onGetuserinfo:e.onGetUserInfo,onContact:e.onContact,onGetphonenumber:e.onGetPhoneNumber,onError:e.onError,onLaunchapp:e.onLaunchApp,onOpensetting:e.onOpenSetting,lang:e.lang,"session-from":e.sessionFrom,"send-message-title":e.sendMessageTitle,"send-message-path":e.sendMessagePath,"send-message-img":e.sendMessageImg,"show-message-card":e.showMessageCard,"app-parameter":e.appParameter},{default:y((()=>[x(b(e.value),1)])),_:1},8,["style","data-index","openType","onGetuserinfo","onContact","onGetphonenumber","onError","onLaunchapp","onOpensetting","lang","session-from","send-message-title","send-message-path","send-message-img","show-message-card","app-parameter"])):(c(),f(o,{key:4,class:m(["u-text__value",[e.type&&`u-text__value--${e.type}`,e.lines&&`u-line-${e.lines}`]]),style:g([l.valueStyle])},{default:y((()=>[x(b(e.value),1)])),_:1},8,["style","class"])),e.suffixIcon?(c(),f(r,{key:5,class:"u-text__suffix-icon"},{default:y((()=>[S(i,{name:e.suffixIcon,customStyle:l.addStyle(e.iconStyle)},null,8,["name","customStyle"])])),_:1})):h("",!0)])),_:1},8,["style","onClick"])):h("",!0)}],["__scopeId","data-v-67bf3f16"]]);export{j as _};
