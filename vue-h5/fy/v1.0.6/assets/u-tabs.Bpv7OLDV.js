import{a0 as e,a1 as t,a2 as a,al as s,a4 as i,C as r,y as l,z as o,I as n,J as d,A as u,a9 as b,G as h,O as c,a3 as p,bi as g,ao as _,bj as f,x as m,H as y,a8 as w,D as v,F as S,E as x,B as C,bk as B}from"./index-V1zTiwCe.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";import{r as N}from"./uni-app.es.CQrvLY-P.js";const T=k({name:"u-badge",mixins:[t,{props:{isDot:{type:Boolean,default:()=>e.badge.isDot},value:{type:[Number,String],default:()=>e.badge.value},modelValue:{type:[Number,String],default:()=>e.badge.modelValue},show:{type:Boolean,default:()=>e.badge.show},max:{type:[Number,String],default:()=>e.badge.max},type:{type:String,default:()=>e.badge.type},showZero:{type:Boolean,default:()=>e.badge.showZero},bgColor:{type:[String,null],default:()=>e.badge.bgColor},color:{type:[String,null],default:()=>e.badge.color},shape:{type:String,default:()=>e.badge.shape},numberType:{type:String,default:()=>e.badge.numberType},offset:{type:Array,default:()=>e.badge.offset},inverted:{type:Boolean,default:()=>e.badge.inverted},absolute:{type:Boolean,default:()=>e.badge.absolute}}},a],computed:{boxStyle:()=>({}),badgeStyle(){const e={};if(this.color&&(e.color=this.color),this.bgColor&&!this.inverted&&(e.backgroundColor=this.bgColor),this.absolute&&(e.position="absolute",this.offset.length)){const t=this.offset[0],a=this.offset[1]||t;e.top=s(t),e.right=s(a)}return e},showValue(){switch(this.numberType){case"overflow":return Number(this.value)>Number(this.max)?this.max+"+":this.value;case"ellipsis":return Number(this.value)>Number(this.max)?"...":this.value;case"limit":return Number(this.value)>999?Number(this.value)>=9999?Math.floor(this.value/1e4*100)/100+"w":Math.floor(this.value/1e3*100)/100+"k":this.value;default:return Number(this.value)}}},methods:{addStyle:i}},[["render",function(e,t,a,s,i,p){const g=c;return e.show&&(0!==Number(e.value)||e.showZero||e.isDot)?(r(),l(g,{key:0,class:u([[e.isDot?"u-badge--dot":"u-badge--not-dot",e.inverted&&"u-badge--inverted","horn"===e.shape&&"u-badge--horn",`u-badge--${e.type}${e.inverted?"--inverted":""}`],"u-badge"]),style:b([p.addStyle(e.customStyle),p.badgeStyle])},{default:o((()=>[n(d(e.isDot?"":p.showValue),1)])),_:1},8,["class","style"])):h("",!0)}],["__scopeId","data-v-0f1c30bc"]]);const $=k({name:"u-tabs",mixins:[t,a,{props:{duration:{type:Number,default:()=>e.tabs.duration},list:{type:Array,default:()=>e.tabs.list},lineColor:{type:String,default:()=>e.tabs.lineColor},activeStyle:{type:[String,Object],default:()=>e.tabs.activeStyle},inactiveStyle:{type:[String,Object],default:()=>e.tabs.inactiveStyle},lineWidth:{type:[String,Number],default:()=>e.tabs.lineWidth},lineHeight:{type:[String,Number],default:()=>e.tabs.lineHeight},lineBgSize:{type:String,default:()=>e.tabs.lineBgSize},itemStyle:{type:[String,Object],default:()=>e.tabs.itemStyle},scrollable:{type:Boolean,default:()=>e.tabs.scrollable},current:{type:[Number,String],default:()=>e.tabs.current},keyName:{type:String,default:()=>e.tabs.keyName}}}],data:()=>({firstTime:!0,scrollLeft:0,scrollViewWidth:0,lineOffsetLeft:0,tabsRect:{left:0},innerCurrent:0,moving:!1}),watch:{current:{immediate:!0,handler(e,t){e!==this.innerCurrent&&(this.innerCurrent=e,this.$nextTick((()=>{this.resize()})))}},list(){this.$nextTick((()=>{this.resize()}))}},computed:{textStyle(){return e=>{const t={},a=e===this.innerCurrent?i(this.activeStyle):i(this.inactiveStyle);return this.list[e].disabled&&(t.color="#c8c9cc"),p(a,t)}},propsBadge:()=>e.badge},async mounted(){this.init()},emits:["click","change"],methods:{addStyle:i,addUnit:s,setLineLeft(){const e=this.list[this.innerCurrent];if(!e)return;let t=this.list.slice(0,this.innerCurrent).reduce(((e,t)=>e+t.rect.width),0);const a=g(this.lineWidth);this.lineOffsetLeft=t+(e.rect.width-a)/2,this.firstTime&&setTimeout((()=>{this.firstTime=!1}),10)},animation(e,t=0){},clickHandler(e,t){this.$emit("click",{...e,index:t},t),e.disabled||(this.innerCurrent=t,this.resize(),this.$emit("change",{...e,index:t},t))},init(){_().then((()=>{this.resize()}))},setScrollLeft(){const e=this.list[this.innerCurrent],t=this.list.slice(0,this.innerCurrent).reduce(((e,t)=>e+t.rect.width),0),a=f().windowWidth;let s=t-(this.tabsRect.width-e.rect.width)/2-(a-this.tabsRect.right)/2+this.tabsRect.left/2;s=Math.min(s,this.scrollViewWidth-this.tabsRect.width),this.scrollLeft=Math.max(0,s)},resize(){0!==this.list.length&&Promise.all([this.getTabsRect(),this.getAllItemRect()]).then((([e,t=[]])=>{this.tabsRect=e,this.scrollViewWidth=0,t.map(((e,t)=>{this.scrollViewWidth+=e.width,this.list[t].rect=e})),this.setLineLeft(),this.setScrollLeft()}))},getTabsRect(){return new Promise((e=>{this.queryRect("u-tabs__wrapper__scroll-view").then((t=>e(t)))}))},getAllItemRect(){return new Promise((e=>{const t=this.list.map(((e,t)=>this.queryRect(`u-tabs__wrapper__nav__item-${t}`,!0)));Promise.all(t).then((t=>e(t)))}))},queryRect(e,t){return new Promise((t=>{this.$uGetRect(`.${e}`).then((e=>{t(e)}))}))}}},[["render",function(e,t,a,s,i,h){const p=c,g=N(m("u-badge"),T),_=C,f=B;return r(),l(_,{class:"u-tabs"},{default:o((()=>[y(_,{class:"u-tabs__wrapper"},{default:o((()=>[w(e.$slots,"left",{},void 0,!0),y(_,{class:"u-tabs__wrapper__scroll-view-wrapper"},{default:o((()=>[y(f,{"scroll-x":e.scrollable,"scroll-left":i.scrollLeft,"scroll-with-animation":"",class:"u-tabs__wrapper__scroll-view","show-scrollbar":!1,ref:"u-tabs__wrapper__scroll-view"},{default:o((()=>[y(_,{class:"u-tabs__wrapper__nav",ref:"u-tabs__wrapper__nav"},{default:o((()=>[(r(!0),v(S,null,x(e.list,((t,a)=>(r(),l(_,{class:u(["u-tabs__wrapper__nav__item",[`u-tabs__wrapper__nav__item-${a}`,t.disabled&&"u-tabs__wrapper__nav__item--disabled"]]),key:a,onClick:e=>h.clickHandler(t,a),ref_for:!0,ref:`u-tabs__wrapper__nav__item-${a}`,style:b([h.addStyle(e.itemStyle),{flex:e.scrollable?"":1}])},{default:o((()=>[y(p,{class:u([[t.disabled&&"u-tabs__wrapper__nav__item__text--disabled"],"u-tabs__wrapper__nav__item__text"]),style:b([h.textStyle(a)])},{default:o((()=>[n(d(t[e.keyName]),1)])),_:2},1032,["class","style"]),y(g,{show:!(!t.badge||!(t.badge.show||t.badge.isDot||t.badge.value)),isDot:t.badge&&t.badge.isDot||h.propsBadge.isDot,value:t.badge&&t.badge.value||h.propsBadge.value,max:t.badge&&t.badge.max||h.propsBadge.max,type:t.badge&&t.badge.type||h.propsBadge.type,showZero:t.badge&&t.badge.showZero||h.propsBadge.showZero,bgColor:t.badge&&t.badge.bgColor||h.propsBadge.bgColor,color:t.badge&&t.badge.color||h.propsBadge.color,shape:t.badge&&t.badge.shape||h.propsBadge.shape,numberType:t.badge&&t.badge.numberType||h.propsBadge.numberType,inverted:t.badge&&t.badge.inverted||h.propsBadge.inverted,customStyle:"margin-left: 4px;"},null,8,["show","isDot","value","max","type","showZero","bgColor","color","shape","numberType","inverted"])])),_:2},1032,["onClick","style","class"])))),128)),y(_,{class:"u-tabs__wrapper__nav__line",ref:"u-tabs__wrapper__nav__line",style:b([{width:h.addUnit(e.lineWidth),transform:`translate(${i.lineOffsetLeft}px)`,transitionDuration:`${i.firstTime?0:e.duration}ms`,height:h.addUnit(e.lineHeight),background:e.lineColor,backgroundSize:e.lineBgSize}])},null,8,["style"])])),_:1},512)])),_:1},8,["scroll-x","scroll-left"])])),_:1}),w(e.$slots,"right",{},void 0,!0)])),_:3})])),_:3})}],["__scopeId","data-v-1d6f2c90"]]);export{$ as _};
