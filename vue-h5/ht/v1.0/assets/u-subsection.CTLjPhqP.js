import{a1 as t,a2 as e,a3 as s,al as i,am as n,an as r,a5 as o,ao as a,y as u,z as l,A as c,H as b,B as h,aa as d,D as m,F as _,E as y,I as C,J as f,C as p,O as g}from"./index-Br34eEhS.js";import{_ as S}from"./_plugin-vue_export-helper.BCo6x5W8.js";const w=S({name:"u-subsection",mixins:[e,s,{props:{list:{type:Array,default:()=>t.subsection.list},current:{type:[String,Number],default:()=>t.subsection.current},activeColor:{type:String,default:()=>t.subsection.activeColor},inactiveColor:{type:String,default:()=>t.subsection.inactiveColor},mode:{type:String,default:()=>t.subsection.mode},fontSize:{type:[String,Number],default:()=>t.subsection.fontSize},bold:{type:Boolean,default:()=>t.subsection.bold},bgColor:{type:String,default:()=>t.subsection.bgColor},keyName:{type:String,default:()=>t.subsection.keyName}}}],data:()=>({itemRect:{width:0,height:0},innerCurrent:"",windowResizeCallback:{}}),watch:{list(t,e){this.init()},current:{immediate:!0,handler(t){t!==this.innerCurrent&&(this.innerCurrent=t)}}},computed:{wrapperStyle(){const t={};return"button"===this.mode&&(t.backgroundColor=this.bgColor),t},barStyle(){const t={};return t.width=`${this.itemRect.width}px`,t.height=`${this.itemRect.height}px`,t.transform=`translateX(${this.innerCurrent*this.itemRect.width}px)`,"subsection"===this.mode&&(t.backgroundColor=this.activeColor),t},itemStyle(t){return t=>{const e={};return"subsection"===this.mode&&(e.borderColor=this.activeColor,e.borderWidth="1px",e.borderStyle="solid"),e}},textStyle(t){return t=>{const e={};return e.fontWeight=this.bold&&this.innerCurrent===t?"bold":"normal",e.fontSize=i(this.fontSize),"subsection"===this.mode?e.color=this.innerCurrent===t?"#fff":this.inactiveColor:e.color=this.innerCurrent===t?this.activeColor:this.inactiveColor,e}}},mounted(){this.init(),this.windowResizeCallback=t=>{this.init()},n(this.windowResizeCallback)},beforeUnmount(){r(this.windowResizeCallback)},emits:["change"],methods:{addStyle:o,init(){this.innerCurrent=this.current,a().then((()=>this.getRect()))},getText(t){return"object"==typeof t?t[this.keyName]:t},getRect(){this.$uGetRect(".u-subsection__item--0").then((t=>{this.itemRect=t}))},clickHandler(t){this.innerCurrent=t,this.$emit("change",t)}}},[["render",function(t,e,s,i,n,r){const o=p,a=g;return u(),l(o,{class:h(["u-subsection",[`u-subsection--${t.mode}`]]),ref:"u-subsection",style:d([r.addStyle(t.customStyle),r.wrapperStyle])},{default:c((()=>[b(o,{class:h(["u-subsection__bar cursor-pointer",["button"===t.mode&&"u-subsection--button__bar",0===n.innerCurrent&&"subsection"===t.mode&&"u-subsection__bar--first",n.innerCurrent>0&&n.innerCurrent<t.list.length-1&&"subsection"===t.mode&&"u-subsection__bar--center",n.innerCurrent===t.list.length-1&&"subsection"===t.mode&&"u-subsection__bar--last"]]),ref:"u-subsection__bar",style:d([r.barStyle])},null,8,["style","class"]),(u(!0),m(_,null,y(t.list,((e,s)=>(u(),l(o,{class:h(["u-subsection__item cursor-pointer",[`u-subsection__item--${s}`,s<t.list.length-1&&"u-subsection__item--no-border-right",0===s&&"u-subsection__item--first",s===t.list.length-1&&"u-subsection__item--last"]]),ref_for:!0,ref:`u-subsection__item--${s}`,style:d([r.itemStyle(s)]),onClick:t=>r.clickHandler(s),key:s},{default:c((()=>[b(a,{class:"u-subsection__item__text",style:d([r.textStyle(s)])},{default:c((()=>[C(f(r.getText(e)),1)])),_:2},1032,["style"])])),_:2},1032,["class","style","onClick"])))),128))])),_:1},8,["class","style"])}],["__scopeId","data-v-a84ee7e1"]]);export{w as _};
