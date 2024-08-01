import{a0 as t,a1 as e,a2 as o,b8 as l,x as a,y as s,z as i,A as r,H as n,B as c,a$ as p,a9 as u,a8 as g,G as d,I as f,J as y,N as m,C as h,O as _}from"./index-BpNRNyt1.js";import{_ as C}from"./u-icon.Bew-d3Qt.js";import{r as b}from"./uni-app.es.DOVXrixg.js";import{_ as x}from"./u-transition.DvGyIFtH.js";import{_ as z}from"./_plugin-vue_export-helper.BCo6x5W8.js";const S=z({name:"u-tag",mixins:[e,o,{props:{type:{type:String,default:()=>t.tag.type},disabled:{type:[Boolean,String],default:()=>t.tag.disabled},size:{type:String,default:()=>t.tag.size},shape:{type:String,default:()=>t.tag.shape},text:{type:[String,Number],default:()=>t.tag.text},bgColor:{type:String,default:()=>t.tag.bgColor},color:{type:String,default:()=>t.tag.color},borderColor:{type:String,default:()=>t.tag.borderColor},closeColor:{type:String,default:()=>t.tag.closeColor},name:{type:[String,Number],default:()=>t.tag.name},plainFill:{type:Boolean,default:()=>t.tag.plainFill},plain:{type:Boolean,default:()=>t.tag.plain},closable:{type:Boolean,default:()=>t.tag.closable},show:{type:Boolean,default:()=>t.tag.show},icon:{type:String,default:()=>t.tag.icon},iconColor:{type:String,default:()=>t.tag.iconColor}}}],data:()=>({}),computed:{style(){const t={};return this.bgColor&&(t.backgroundColor=this.bgColor),this.color&&(t.color=this.color),this.borderColor&&(t.borderColor=this.borderColor),t},textColor(){const t={};return this.color&&(t.color=this.color),t},imgStyle(){const t="large"===this.size?"17px":"medium"===this.size?"15px":"13px";return{width:t,height:t}},closeSize(){return"large"===this.size?15:"medium"===this.size?13:12},iconSize(){return"large"===this.size?21:"medium"===this.size?19:16},elIconColor(){return this.iconColor?this.iconColor:this.plain?this.type:"#ffffff"}},emits:["click","close"],methods:{testImage:l.image,closeHandler(){this.$emit("close",this.name)},clickHandler(){this.$emit("click",this.name)}}},[["render",function(t,e,o,l,z,S){const k=m,$=b(a("u-icon"),C),w=h,B=_,I=b(a("u-transition"),x);return s(),i(I,{mode:"fade",show:t.show,style:{display:"inline-flex"}},{default:r((()=>[n(w,{class:"u-tag-wrapper cursor-pointer"},{default:r((()=>[n(w,{class:c(["u-tag",[`u-tag--${t.shape}`,!t.plain&&`u-tag--${t.type}`,t.plain&&`u-tag--${t.type}--plain`,`u-tag--${t.size}`,t.plain&&t.plainFill&&`u-tag--${t.type}--plain--fill`]]),onClick:p(S.clickHandler,["stop"]),style:u([{marginRight:t.closable?"10px":0,marginTop:t.closable?"10px":0},S.style])},{default:r((()=>[g(t.$slots,"icon",{},(()=>[t.icon?(s(),i(w,{key:0,class:"u-tag__icon"},{default:r((()=>[S.testImage(t.icon)?(s(),i(k,{key:0,src:t.icon,style:u([S.imgStyle])},null,8,["src","style"])):(s(),i($,{key:1,color:S.elIconColor,name:t.icon,size:S.iconSize},null,8,["color","name","size"]))])),_:1})):d("",!0)]),!0),n(B,{class:c(["u-tag__text",[`u-tag__text--${t.type}`,t.plain&&`u-tag__text--${t.type}--plain`,`u-tag__text--${t.size}`]]),style:u([S.textColor])},{default:r((()=>[f(y(t.text),1)])),_:1},8,["style","class"])])),_:3},8,["class","onClick","style"]),t.closable?(s(),i(w,{key:0,class:c(["u-tag__close",[`u-tag__close--${t.size}`]]),onClick:p(S.closeHandler,["stop"]),style:u({backgroundColor:t.closeColor})},{default:r((()=>[n($,{name:"close",size:S.closeSize,color:"#ffffff"},null,8,["size"])])),_:1},8,["class","onClick","style"])):d("",!0)])),_:3})])),_:3},8,["show"])}],["__scopeId","data-v-aebd36f7"]]);export{S as _};
