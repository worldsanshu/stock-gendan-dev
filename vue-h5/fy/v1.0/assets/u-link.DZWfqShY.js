import{a1 as e,a2 as t,a3 as n,al as i,aU as l,a5 as o,y as a,z as s,A as r,I as p,J as d,a$ as u,aa as f,O as c}from"./index-DgmPZ0G5.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";const S=k({name:"u-link",mixins:[t,n,{props:{color:{type:String,default:()=>e.link.color},fontSize:{type:[String,Number],default:()=>e.link.fontSize},underLine:{type:Boolean,default:()=>e.link.underLine},href:{type:String,default:()=>e.link.href},mpTips:{type:String,default:()=>e.link.mpTips},lineColor:{type:String,default:()=>e.link.lineColor},text:{type:String,default:()=>e.link.text}}}],computed:{linkStyle(){return{color:this.color,fontSize:i(this.fontSize),lineHeight:i(l(this.fontSize)+2),textDecoration:this.underLine?"underline":"none"}}},emits:["click"],methods:{addStyle:o,openLink(){window.open(this.href),this.$emit("click")}}},[["render",function(e,t,n,i,l,o){const k=c;return a(),s(k,{class:"u-link",onClick:u(o.openLink,["stop"]),style:f([o.linkStyle,o.addStyle(e.customStyle)])},{default:r((()=>[p(d(e.text),1)])),_:1},8,["onClick","style"])}],["__scopeId","data-v-0f5fca42"]]);export{S as _};
