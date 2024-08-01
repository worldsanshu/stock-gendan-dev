import{a1 as e,a2 as a,a3 as i,al as o,a4 as t,a5 as r,x as s,y as d,z as n,A as l,H as h,aa as g,G as u,a9 as m,N as p,C as c}from"./index-DAL43Vnu.js";import{_ as y}from"./u-icon.CQfWR99v.js";import{r as w}from"./uni-app.es.b_6IPGg9.js";import{_ as f}from"./u-transition.Ozm9cBsE.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";const _=b({name:"u-image",mixins:[a,i,{props:{src:{type:String,default:()=>e.image.src},mode:{type:String,default:()=>e.image.mode},width:{type:[String,Number],default:()=>e.image.width},height:{type:[String,Number],default:()=>e.image.height},shape:{type:String,default:()=>e.image.shape},radius:{type:[String,Number],default:()=>e.image.radius},lazyLoad:{type:Boolean,default:()=>e.image.lazyLoad},showMenuByLongpress:{type:Boolean,default:()=>e.image.showMenuByLongpress},loadingIcon:{type:String,default:()=>e.image.loadingIcon},errorIcon:{type:String,default:()=>e.image.errorIcon},showLoading:{type:Boolean,default:()=>e.image.showLoading},showError:{type:Boolean,default:()=>e.image.showError},fade:{type:Boolean,default:()=>e.image.fade},webp:{type:Boolean,default:()=>e.image.webp},duration:{type:[String,Number],default:()=>e.image.duration},bgColor:{type:String,default:()=>e.image.bgColor}}}],data(){return{isError:!1,loading:!0,opacity:1,durationTime:this.duration,backgroundStyle:{},show:!1}},watch:{src:{immediate:!0,handler(e){e?(this.isError=!1,this.loading=!0):this.isError=!0}}},computed:{wrapStyle(){let e={};return e.width=o(this.width),e.height=o(this.height),e.borderRadius="circle"==this.shape?"10000px":o(this.radius),e.overflow=this.radius>0?"hidden":"visible",t(e,r(this.customStyle))}},mounted(){this.show=!0},emits:["click","error","load"],methods:{addUnit:o,onClick(){this.$emit("click")},onErrorHandler(e){this.loading=!1,this.isError=!0,this.$emit("error",e)},onLoadHandler(e){this.loading=!1,this.isError=!1,this.$emit("load",e),this.removeBgColor()},removeBgColor(){this.backgroundStyle={backgroundColor:"transparent"}}}},[["render",function(e,a,i,o,t,r){const b=p,_=w(s("u-icon"),y),S=c,k=w(s("u-transition"),f);return d(),n(k,{mode:"fade",show:t.show,duration:e.fade?1e3:0},{default:l((()=>[h(S,{class:"u-image",onClick:r.onClick,style:g([r.wrapStyle,t.backgroundStyle])},{default:l((()=>[t.isError?u("",!0):(d(),n(b,{key:0,src:e.src,mode:e.mode,onError:r.onErrorHandler,onLoad:r.onLoadHandler,"show-menu-by-longpress":e.showMenuByLongpress,"lazy-load":e.lazyLoad,class:"u-image__image",style:g({borderRadius:"circle"==e.shape?"10000px":r.addUnit(e.radius),width:r.addUnit(e.width),height:r.addUnit(e.height)})},null,8,["src","mode","onError","onLoad","show-menu-by-longpress","lazy-load","style"])),e.showLoading&&t.loading?(d(),n(S,{key:1,class:"u-image__loading",style:g({borderRadius:"circle"==e.shape?"50%":r.addUnit(e.radius),backgroundColor:this.bgColor,width:r.addUnit(e.width),height:r.addUnit(e.height)})},{default:l((()=>[m(e.$slots,"loading",{},(()=>[h(_,{name:e.loadingIcon,width:e.width,height:e.height},null,8,["name","width","height"])]),!0)])),_:3},8,["style"])):u("",!0),e.showError&&t.isError&&!t.loading?(d(),n(S,{key:2,class:"u-image__error",style:g({borderRadius:"circle"==e.shape?"50%":r.addUnit(e.radius),width:r.addUnit(e.width),height:r.addUnit(e.height)})},{default:l((()=>[m(e.$slots,"error",{},(()=>[h(_,{name:e.errorIcon,width:e.width,height:e.height},null,8,["name","width","height"])]),!0)])),_:3},8,["style"])):u("",!0)])),_:3},8,["onClick","style"])])),_:3},8,["show","duration"])}],["__scopeId","data-v-d1c1653c"]]);export{_};
