import{_ as o}from"./u-transition.V3RkxVWT.js";import{a0 as t,a1 as e,a2 as s,a3 as a,a4 as l,x as i,y as r,z as n,A as u,a8 as p,B as d,a9 as c,C as m,bi as y,al as f,G as h,H as v,bo as b}from"./index-C6SCDi5e.js";import{r as _}from"./uni-app.es.B0qxMUeI.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";import{_ as g}from"./u-status-bar.Q8exSR7e.js";import{_ as S}from"./u-icon.DeyhXk6x.js";const I=k({name:"u-overlay",mixins:[e,s,{props:{show:{type:Boolean,default:()=>t.overlay.show},zIndex:{type:[String,Number],default:()=>t.overlay.zIndex},duration:{type:[String,Number],default:()=>t.overlay.duration},opacity:{type:[String,Number],default:()=>t.overlay.opacity}}}],computed:{overlayStyle(){const o={position:"fixed",top:0,left:0,right:0,zIndex:this.zIndex,bottom:0,"background-color":`rgba(0, 0, 0, ${this.opacity})`};return a(o,l(this.customStyle))}},emits:["click"],methods:{clickHandler(){this.$emit("click")}}},[["render",function(t,e,s,a,l,d){const c=_(i("u-transition"),o);return r(),n(c,{show:t.show,"custom-class":"u-overlay",duration:t.duration,"custom-style":d.overlayStyle,onClick:d.clickHandler},{default:u((()=>[p(t.$slots,"default",{},void 0,!0)])),_:3},8,["show","duration","custom-style","onClick"])}],["__scopeId","data-v-acd95985"]]);const C=k({name:"u-safe-bottom",mixins:[e,s,{props:{}}],data:()=>({safeAreaBottomHeight:0,isNvue:!1}),computed:{style(){return a({},l(this.customStyle))}},mounted(){}},[["render",function(o,t,e,s,a,l){const i=m;return r(),n(i,{class:d(["u-safe-bottom",[!a.isNvue&&"u-safe-area-inset-bottom"]]),style:c([l.style])},null,8,["style","class"])}],["__scopeId","data-v-c4161810"]]);const x=k({name:"u-popup",mixins:[e,s,{props:{show:{type:Boolean,default:()=>t.popup.show},overlay:{type:Boolean,default:()=>t.popup.overlay},mode:{type:String,default:()=>t.popup.mode},duration:{type:[String,Number],default:()=>t.popup.duration},closeable:{type:Boolean,default:()=>t.popup.closeable},overlayStyle:{type:[Object,String],default:()=>t.popup.overlayStyle},closeOnClickOverlay:{type:Boolean,default:()=>t.popup.closeOnClickOverlay},zIndex:{type:[String,Number],default:()=>t.popup.zIndex},safeAreaInsetBottom:{type:Boolean,default:()=>t.popup.safeAreaInsetBottom},safeAreaInsetTop:{type:Boolean,default:()=>t.popup.safeAreaInsetTop},closeIconPos:{type:String,default:()=>t.popup.closeIconPos},round:{type:[Boolean,String,Number],default:()=>t.popup.round},zoom:{type:Boolean,default:()=>t.popup.zoom},bgColor:{type:String,default:()=>t.popup.bgColor},overlayOpacity:{type:[Number,String],default:()=>t.popup.overlayOpacity}}}],data(){return{overlayDuration:this.duration+50}},watch:{show(o,t){}},computed:{transitionStyle(){const o={zIndex:this.zIndex,position:"fixed",display:"flex"};return o[this.mode]=0,"left"===this.mode||"right"===this.mode?a(o,{bottom:0,top:0}):"top"===this.mode||"bottom"===this.mode?a(o,{left:0,right:0}):"center"===this.mode?a(o,{alignItems:"center","justify-content":"center",top:0,left:0,right:0,bottom:0}):void 0},contentStyle(){const o={};if(y(),"center"!==this.mode&&(o.flex=1),this.bgColor&&(o.backgroundColor=this.bgColor),this.round){const t=f(this.round);"top"===this.mode?(o.borderBottomLeftRadius=t,o.borderBottomRightRadius=t):"bottom"===this.mode?(o.borderTopLeftRadius=t,o.borderTopRightRadius=t):"center"===this.mode&&(o.borderRadius=t)}return a(o,l(this.customStyle))},position(){return"center"===this.mode?this.zoom?"fade-zoom":"fade":"left"===this.mode?"slide-left":"right"===this.mode?"slide-right":"bottom"===this.mode?"slide-up":"top"===this.mode?"slide-down":void 0}},emits:["open","close","click"],methods:{overlayClick(){this.closeOnClickOverlay&&this.$emit("close")},close(o){this.$emit("close")},afterEnter(){this.$emit("open")},clickHandler(){"center"===this.mode&&this.overlayClick(),this.$emit("click")}}},[["render",function(t,e,s,a,l,y){const f=_(i("u-overlay"),I),k=_(i("u-status-bar"),g),x=_(i("u-icon"),S),w=m,z=_(i("u-safe-bottom"),C),B=_(i("u-transition"),o);return r(),n(w,{class:"u-popup"},{default:u((()=>[t.overlay?(r(),n(f,{key:0,show:t.show,onClick:y.overlayClick,zIndex:t.zIndex,duration:l.overlayDuration,customStyle:t.overlayStyle,opacity:t.overlayOpacity},null,8,["show","onClick","zIndex","duration","customStyle","opacity"])):h("",!0),v(B,{show:t.show,customStyle:y.transitionStyle,mode:y.position,duration:t.duration,onAfterEnter:y.afterEnter,onClick:y.clickHandler},{default:u((()=>[v(w,{class:"u-popup__content",style:c([y.contentStyle]),onClick:b(t.noop,["stop"])},{default:u((()=>[t.safeAreaInsetTop?(r(),n(k,{key:0})):h("",!0),p(t.$slots,"default",{},void 0,!0),t.closeable?(r(),n(w,{key:1,onClick:b(y.close,["stop"]),class:d(["u-popup__content__close",["u-popup__content__close--"+t.closeIconPos]]),"hover-class":"u-popup__content__close--hover","hover-stay-time":"150"},{default:u((()=>[v(x,{name:"close",color:"#909399",size:"18",bold:""})])),_:1},8,["onClick","class"])):h("",!0),t.safeAreaInsetBottom?(r(),n(z,{key:2})):h("",!0)])),_:3},8,["style","onClick"])])),_:3},8,["show","customStyle","mode","duration","onAfterEnter","onClick"])])),_:3})}],["__scopeId","data-v-ddf5f9f1"]]);export{x as _};
