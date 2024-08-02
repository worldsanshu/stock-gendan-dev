import{a1 as e,a2 as t,a3 as i,al as s,a5 as a,x as l,y as o,z as c,A as n,H as r,aa as h,B as u,C as d}from"./index-C2GIS8XA.js";import{_ as m}from"./u-loading-icon.DCmWVxWx.js";import{r as f}from"./uni-app.es.D-mx81Kh.js";import{_ as p}from"./_plugin-vue_export-helper.BCo6x5W8.js";const g=p({name:"u-switch",mixins:[t,i,{props:{loading:{type:Boolean,default:()=>e.switch.loading},disabled:{type:Boolean,default:()=>e.switch.disabled},size:{type:[String,Number],default:()=>e.switch.size},activeColor:{type:String,default:()=>e.switch.activeColor},inactiveColor:{type:String,default:()=>e.switch.inactiveColor},modelValue:{type:[Boolean,String,Number],default:()=>e.switch.value},activeValue:{type:[String,Number,Boolean],default:()=>e.switch.activeValue},inactiveValue:{type:[String,Number,Boolean],default:()=>e.switch.inactiveValue},asyncChange:{type:Boolean,default:()=>e.switch.asyncChange},space:{type:[String,Number],default:()=>e.switch.space}}}],watch:{modelValue:{immediate:!0,handler(e){e!==this.inactiveValue&&this.activeValue}}},data:()=>({bgColor:"#ffffff"}),computed:{isActive(){return this.modelValue===this.activeValue},switchStyle(){let e={};return e.width=s(2*this.size+2),e.height=s(Number(this.size)+2),this.customInactiveColor&&(e.borderColor="rgba(0, 0, 0, 0)"),e.backgroundColor=this.isActive?this.activeColor:this.inactiveColor,e},nodeStyle(){let e={};e.width=s(this.size-this.space),e.height=s(this.size-this.space);const t=this.isActive?s(this.space):s(this.size);return e.transform=`translateX(-${t})`,e},bgStyle(){let e={};return e.width=s(2*Number(this.size)-this.size/2),e.height=s(this.size),e.backgroundColor=this.inactiveColor,e.transform=`scale(${this.isActive?0:1})`,e},customInactiveColor(){return"#fff"!==this.inactiveColor&&"#ffffff"!==this.inactiveColor}},emits:["update:modelValue","change"],methods:{addStyle:a,clickHandler(){if(!this.disabled&&!this.loading){const e=this.isActive?this.inactiveValue:this.activeValue;this.asyncChange||this.$emit("update:modelValue",e),this.$nextTick((()=>{this.$emit("change",e)}))}}}},[["render",function(e,t,i,s,a,p){const g=d,v=f(l("u-loading-icon"),m);return o(),c(g,{class:u(["u-switch cursor-pointer",[e.disabled&&"u-switch--disabled"]]),style:h([p.switchStyle,p.addStyle(e.customStyle)]),onClick:p.clickHandler},{default:n((()=>[r(g,{class:"u-switch__bg",style:h([p.bgStyle])},null,8,["style"]),r(g,{class:u(["u-switch__node",[e.modelValue&&"u-switch__node--on"]]),style:h([p.nodeStyle]),ref:"u-switch__node"},{default:n((()=>[r(v,{show:e.loading,mode:"circle",timingFunction:"linear",color:e.modelValue?e.activeColor:"#AAABAD",size:.6*e.size},null,8,["show","color","size"])])),_:1},8,["class","style"])])),_:1},8,["class","style","onClick"])}],["__scopeId","data-v-f2e8c303"]]);export{g as _};
