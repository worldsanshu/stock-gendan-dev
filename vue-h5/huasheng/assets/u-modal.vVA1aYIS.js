import{a1 as o,a2 as t,a3 as e,al as a,x as l,y as n,z as s,A as r,H as c,aa as i,I as u,J as d,G as m,a9 as p,D as _,F as f,B as y,O as h,C as w}from"./index-DgmPZ0G5.js";import{_ as g}from"./u-line.PUIrZZ8-.js";import{r as C}from"./uni-app.es.DuVg4RGY.js";import{_ as B}from"./u-loading-icon.Dk-dhO9O.js";import{_ as v}from"./u-popup.DTXTupYs.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";const b=k({name:"u-modal",mixins:[t,e,{props:{show:{type:Boolean,default:()=>o.modal.show},title:{type:[String],default:()=>o.modal.title},content:{type:String,default:()=>o.modal.content},confirmText:{type:String,default:()=>o.modal.confirmText},cancelText:{type:String,default:()=>o.modal.cancelText},showConfirmButton:{type:Boolean,default:()=>o.modal.showConfirmButton},showCancelButton:{type:Boolean,default:()=>o.modal.showCancelButton},confirmColor:{type:String,default:()=>o.modal.confirmColor},cancelColor:{type:String,default:()=>o.modal.cancelColor},buttonReverse:{type:Boolean,default:()=>o.modal.buttonReverse},zoom:{type:Boolean,default:()=>o.modal.zoom},asyncClose:{type:Boolean,default:()=>o.modal.asyncClose},closeOnClickOverlay:{type:Boolean,default:()=>o.modal.closeOnClickOverlay},negativeTop:{type:[String,Number],default:()=>o.modal.negativeTop},width:{type:[String,Number],default:()=>o.modal.width},confirmButtonShape:{type:String,default:()=>o.modal.confirmButtonShape}}}],data:()=>({loading:!1}),watch:{show(o){o&&this.loading&&(this.loading=!1)}},emits:["confirm","cancel","close"],methods:{addUnit:a,confirmHandler(){this.asyncClose&&(this.loading=!0),this.$emit("confirm")},cancelHandler(){this.$emit("cancel")},clickHandler(){this.closeOnClickOverlay&&this.$emit("close")}}},[["render",function(o,t,e,a,k,b){const x=h,O=w,S=C(l("u-line"),g),T=C(l("u-loading-icon"),B),H=C(l("u-popup"),v);return n(),s(H,{mode:"center",zoom:o.zoom,show:o.show,customStyle:{borderRadius:"6px",overflow:"hidden",marginTop:`-${b.addUnit(o.negativeTop)}`},closeOnClickOverlay:o.closeOnClickOverlay,safeAreaInsetBottom:!1,duration:400,onClick:b.clickHandler},{default:r((()=>[c(O,{class:"u-modal",style:i({width:b.addUnit(o.width)})},{default:r((()=>[o.title?(n(),s(x,{key:0,class:"u-modal__title"},{default:r((()=>[u(d(o.title),1)])),_:1})):m("",!0),c(O,{class:"u-modal__content",style:i({paddingTop:(o.title?12:25)+"px"})},{default:r((()=>[p(o.$slots,"default",{},(()=>[c(x,{class:"u-modal__content__text"},{default:r((()=>[u(d(o.content),1)])),_:1})]),!0)])),_:3},8,["style"]),o.$slots.confirmButton?(n(),s(O,{key:1,class:"u-modal__button-group--confirm-button"},{default:r((()=>[p(o.$slots,"confirmButton",{},void 0,!0)])),_:3})):(n(),_(f,{key:2},[c(S),c(O,{class:"u-modal__button-group",style:i({flexDirection:o.buttonReverse?"row-reverse":"row"})},{default:r((()=>[o.showCancelButton?(n(),s(O,{key:0,class:y(["u-modal__button-group__wrapper u-modal__button-group__wrapper--cancel",[o.showCancelButton&&!o.showConfirmButton&&"u-modal__button-group__wrapper--only-cancel"]]),"hover-stay-time":150,"hover-class":"u-modal__button-group__wrapper--hover",onClick:b.cancelHandler},{default:r((()=>[c(x,{class:"u-modal__button-group__wrapper__text",style:i({color:o.cancelColor})},{default:r((()=>[u(d(o.cancelText),1)])),_:1},8,["style"])])),_:1},8,["class","onClick"])):m("",!0),o.showConfirmButton&&o.showCancelButton?(n(),s(S,{key:1,direction:"column"})):m("",!0),o.showConfirmButton?(n(),s(O,{key:2,class:y(["u-modal__button-group__wrapper u-modal__button-group__wrapper--confirm",[!o.showCancelButton&&o.showConfirmButton&&"u-modal__button-group__wrapper--only-confirm"]]),"hover-stay-time":150,"hover-class":"u-modal__button-group__wrapper--hover",onClick:b.confirmHandler},{default:r((()=>[k.loading?(n(),s(T,{key:0})):(n(),s(x,{key:1,class:"u-modal__button-group__wrapper__text",style:i({color:o.confirmColor})},{default:r((()=>[u(d(o.confirmText),1)])),_:1},8,["style"]))])),_:1},8,["class","onClick"])):m("",!0)])),_:1},8,["style"])],64))])),_:3},8,["style"])])),_:3},8,["zoom","show","customStyle","closeOnClickOverlay","onClick"])}],["__scopeId","data-v-e50fa44c"]]);export{b as _};
