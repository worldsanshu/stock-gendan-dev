import{_ as e}from"./u-icon.CwZ5kuOL.js";import{a1 as t,a2 as a,a3 as i,al as l,b5 as o,a4 as r,a5 as s,bh as n,x as d,y as c,z as h,A as p,H as u,B as b,a$ as m,aa as C,a9 as S,I as f,J as D,C as g,O as y}from"./index-DHIuICwK.js";import{r as z}from"./uni-app.es.d3PbdijJ.js";import{_ as v}from"./_plugin-vue_export-helper.BCo6x5W8.js";const k=v({name:"u-radio",mixins:[a,i,{props:{name:{type:[String,Number,Boolean],default:()=>t.radio.name},shape:{type:String,default:()=>t.radio.shape},disabled:{type:[String,Boolean],default:()=>t.radio.disabled},labelDisabled:{type:[String,Boolean],default:()=>t.radio.labelDisabled},activeColor:{type:String,default:()=>t.radio.activeColor},inactiveColor:{type:String,default:()=>t.radio.inactiveColor},iconSize:{type:[String,Number],default:()=>t.radio.iconSize},labelSize:{type:[String,Number],default:()=>t.radio.labelSize},label:{type:[String,Number],default:()=>t.radio.label},size:{type:[String,Number],default:()=>t.radio.size},color:{type:String,default:()=>t.radio.color},labelColor:{type:String,default:()=>t.radio.labelColor},iconColor:{type:String,default:()=>t.radio.iconColor}}}],data:()=>({checked:!1,parentData:{iconSize:12,labelDisabled:null,disabled:null,shape:null,activeColor:null,inactiveColor:null,size:18,value:null,modelValue:null,iconColor:null,placement:"row",borderBottom:!1,iconPlacement:"left"}}),computed:{elDisabled(){return""!==this.disabled?this.disabled:null!==this.parentData.disabled&&this.parentData.disabled},elLabelDisabled(){return""!==this.labelDisabled?this.labelDisabled:null!==this.parentData.labelDisabled&&this.parentData.labelDisabled},elSize(){return this.size?this.size:this.parentData.size?this.parentData.size:21},elIconSize(){return this.iconSize?this.iconSize:this.parentData.iconSize?this.parentData.iconSize:12},elActiveColor(){return this.activeColor?this.activeColor:this.parentData.activeColor?this.parentData.activeColor:"#2979ff"},elInactiveColor(){return this.inactiveColor?this.inactiveColor:this.parentData.inactiveColor?this.parentData.inactiveColor:"#c8c9cc"},elLabelColor(){return this.labelColor?this.labelColor:this.parentData.labelColor?this.parentData.labelColor:"#606266"},elShape(){return this.shape?this.shape:this.parentData.shape?this.parentData.shape:"circle"},elLabelSize(){return l(this.labelSize?this.labelSize:this.parentData.labelSize?this.parentData.labelSize:"15")},elIconColor(){const e=this.iconColor?this.iconColor:this.parentData.iconColor?this.parentData.iconColor:"#ffffff";return this.elDisabled?this.checked?this.elInactiveColor:"transparent":this.checked?e:"transparent"},iconClasses(){let e=[];return e.push("u-radio__icon-wrap--"+this.elShape),this.elDisabled&&e.push("u-radio__icon-wrap--disabled"),this.checked&&this.elDisabled&&e.push("u-radio__icon-wrap--disabled--checked"),e},iconWrapStyle(){const e={};return e.backgroundColor=this.checked&&!this.elDisabled?this.elActiveColor:"#ffffff",e.borderColor=this.checked&&!this.elDisabled?this.elActiveColor:this.elInactiveColor,e.width=l(this.elSize),e.height=l(this.elSize),"right"===this.parentData.iconPlacement&&(e.marginRight=0),e},radioStyle(){const e={};return this.parentData.borderBottom&&this.parentData.placement,this.parentData.borderBottom&&"column"===this.parentData.placement&&(e.paddingBottom="ios"===o()?"12px":"8px"),r(e,s(this.customStyle))}},mounted(){this.init()},emits:["change"],methods:{init(){this.updateParentData(),this.parent,this.checked=this.name===this.parentData.modelValue},updateParentData(){this.getParentData("u-radio-group")},iconClickHandler(e){this.preventEvent(e),this.elDisabled||this.setRadioCheckedStatus()},wrapperClickHandler(e){"right"===this.parentData.iconPlacement&&this.iconClickHandler(e)},labelClickHandler(e){this.preventEvent(e),this.elLabelDisabled||this.elDisabled||this.setRadioCheckedStatus()},emitEvent(){this.checked||(this.$emit("change",this.name),this.$nextTick((()=>{n(this,"change")})))},setRadioCheckedStatus(){this.emitEvent(),this.checked=!0,"function"==typeof this.parent.unCheckedOther&&this.parent.unCheckedOther(this)}}},[["render",function(t,a,i,l,o,r){const s=z(d("u-icon"),e),n=g,v=y;return c(),h(n,{class:b(["u-radio cursor-pointer",[`u-radio-label--${o.parentData.iconPlacement}`,o.parentData.borderBottom&&"column"===o.parentData.placement&&"u-border-bottom"]]),onClick:m(r.wrapperClickHandler,["stop"]),style:C([r.radioStyle])},{default:p((()=>[u(n,{class:b(["u-radio__icon-wrap cursor-pointer",r.iconClasses]),onClick:m(r.iconClickHandler,["stop"]),style:C([r.iconWrapStyle])},{default:p((()=>[S(t.$slots,"icon",{},(()=>[u(s,{class:"u-radio__icon-wrap__icon",name:"checkbox-mark",size:r.elIconSize,color:r.elIconColor},null,8,["size","color"])]),!0)])),_:3},8,["onClick","class","style"]),u(v,{class:"u-radio__text",onClick:m(r.labelClickHandler,["stop"]),style:C({color:r.elDisabled?r.elInactiveColor:r.elLabelColor,fontSize:r.elLabelSize,lineHeight:r.elLabelSize})},{default:p((()=>[f(D(t.label),1)])),_:1},8,["onClick","style"])])),_:3},8,["onClick","style","class"])}],["__scopeId","data-v-c5c6c035"]]);const _=v({name:"u-radio-group",mixins:[a,i,{props:{modelValue:{type:[String,Number,Boolean],default:()=>t.radioGroup.value},disabled:{type:Boolean,default:()=>t.radioGroup.disabled},shape:{type:String,default:()=>t.radioGroup.shape},activeColor:{type:String,default:()=>t.radioGroup.activeColor},inactiveColor:{type:String,default:()=>t.radioGroup.inactiveColor},name:{type:String,default:()=>t.radioGroup.name},size:{type:[String,Number],default:()=>t.radioGroup.size},placement:{type:String,default:()=>t.radioGroup.placement},label:{type:[String],default:()=>t.radioGroup.label},labelColor:{type:[String],default:()=>t.radioGroup.labelColor},labelSize:{type:[String,Number],default:()=>t.radioGroup.labelSize},labelDisabled:{type:Boolean,default:()=>t.radioGroup.labelDisabled},iconColor:{type:String,default:()=>t.radioGroup.iconColor},iconSize:{type:[String,Number],default:()=>t.radioGroup.iconSize},borderBottom:{type:Boolean,default:()=>t.radioGroup.borderBottom},iconPlacement:{type:String,default:()=>t.radio.iconPlacement}}}],computed:{parentData(){return[this.modelValue,this.disabled,this.inactiveColor,this.activeColor,this.size,this.labelDisabled,this.shape,this.iconSize,this.borderBottom,this.placement]},bemClass(){return this.bem("radio-group",["placement"])}},watch:{parentData(){this.children.length&&this.children.map((e=>{"function"==typeof e.init&&e.init()}))}},data:()=>({}),created(){this.children=[]},emits:["update:modelValue","change"],methods:{unCheckedOther(e){this.children.map((t=>{e!==t&&(t.checked=!1)}));const{name:t}=e;this.$emit("update:modelValue",t),this.$emit("change",t)}}},[["render",function(e,t,a,i,l,o){const r=g;return c(),h(r,{class:b(["u-radio-group",o.bemClass])},{default:p((()=>[S(e.$slots,"default",{},void 0,!0)])),_:3},8,["class"])}],["__scopeId","data-v-43aa91d4"]]);export{k as _,_ as a};
