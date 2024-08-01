import{a1 as e,a2 as o,a3 as t,aZ as i,al as a,a5 as n,a_ as r,y as d,z as l,A as s,B as c,aa as u,D as g,F as m,E as f,G as y,I as p,J as h,C as v,O as _}from"./index-B_R0yMjY.js";import{_ as w}from"./_plugin-vue_export-helper.BCo6x5W8.js";const C=w({name:"u-loading-icon",mixins:[o,t,{props:{show:{type:Boolean,default:()=>e.loadingIcon.show},color:{type:String,default:()=>e.loadingIcon.color},textColor:{type:String,default:()=>e.loadingIcon.textColor},vertical:{type:Boolean,default:()=>e.loadingIcon.vertical},mode:{type:String,default:()=>e.loadingIcon.mode},size:{type:[String,Number],default:()=>e.loadingIcon.size},textSize:{type:[String,Number],default:()=>e.loadingIcon.textSize},text:{type:[String,Number],default:()=>e.loadingIcon.text},timingFunction:{type:String,default:()=>e.loadingIcon.timingFunction},duration:{type:[String,Number],default:()=>e.loadingIcon.duration},inactiveColor:{type:String,default:()=>e.loadingIcon.inactiveColor}}}],data:()=>({array12:Array.from({length:12}),aniAngel:360,webviewHide:!1,loading:!1}),computed:{otherBorderColor(){const e=i(this.color,"#ffffff",100)[80];return"circle"===this.mode?this.inactiveColor?this.inactiveColor:e:"transparent"}},watch:{show(e){}},mounted(){this.init()},methods:{addUnit:a,addStyle:n,init(){setTimeout((()=>{}),20)},addEventListenerToWebview(){const e=r(),o=e[e.length-1].$getAppWebview();o.addEventListener("hide",(()=>{this.webviewHide=!0})),o.addEventListener("show",(()=>{this.webviewHide=!1}))}}},[["render",function(e,o,t,i,a,n){const r=v,w=_;return e.show?(d(),l(r,{key:0,class:c(["u-loading-icon",[e.vertical&&"u-loading-icon--vertical"]]),style:u([n.addStyle(e.customStyle)])},{default:s((()=>[a.webviewHide?y("",!0):(d(),l(r,{key:0,class:c(["u-loading-icon__spinner",[`u-loading-icon__spinner--${e.mode}`]]),ref:"ani",style:u({color:e.color,width:n.addUnit(e.size),height:n.addUnit(e.size),borderTopColor:e.color,borderBottomColor:n.otherBorderColor,borderLeftColor:n.otherBorderColor,borderRightColor:n.otherBorderColor,"animation-duration":`${e.duration}ms`,"animation-timing-function":"semicircle"===e.mode||"circle"===e.mode?e.timingFunction:""})},{default:s((()=>["spinner"===e.mode?(d(!0),g(m,{key:0},f(a.array12,((e,o)=>(d(),l(r,{key:o,class:"u-loading-icon__dot"})))),128)):y("",!0)])),_:1},8,["class","style"])),e.text?(d(),l(w,{key:1,class:"u-loading-icon__text",style:u({fontSize:n.addUnit(e.textSize),color:e.textColor})},{default:s((()=>[p(h(e.text),1)])),_:1},8,["style"])):y("",!0)])),_:1},8,["style","class"])):y("",!0)}],["__scopeId","data-v-40fecea9"]]);export{C as _};
