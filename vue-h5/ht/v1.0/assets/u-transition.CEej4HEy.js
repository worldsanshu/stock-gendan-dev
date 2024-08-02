import{a1 as t,ah as e,ao as i,a5 as s,a2 as a,a3 as n,y as o,z as r,A as l,a9 as d,B as u,aa as h,G as c,C as m}from"./index-DhC4eQBs.js";import{_ as v}from"./_plugin-vue_export-helper.BCo6x5W8.js";const f={props:{show:{type:Boolean,default:()=>t.transition.show},mode:{type:String,default:()=>t.transition.mode},duration:{type:[String,Number],default:()=>t.transition.duration},timingFunction:{type:String,default:()=>t.transition.timingFunction}}},p=t=>({enter:`u-${t}-enter u-${t}-enter-active`,"enter-to":`u-${t}-enter-to u-${t}-enter-active`,leave:`u-${t}-leave u-${t}-leave-active`,"leave-to":`u-${t}-leave-to u-${t}-leave-active`});const y=v({name:"u-transition",data:()=>({inited:!1,viewStyle:{},status:"",transitionEnded:!1,display:!1,classes:""}),emits:["click","beforeEnter","enter","afterEnter","beforeLeave","leave","afterLeave"],computed:{mergeStyle(){const{viewStyle:t,customStyle:e}=this;return{transitionDuration:`${this.duration}ms`,transitionTimingFunction:this.timingFunction,...s(e),...t}}},mixins:[a,n,{methods:{clickHandler(){this.$emit("click")},async vueEnter(){const t=p(this.mode);this.status="enter",this.$emit("beforeEnter"),this.inited=!0,this.display=!0,this.classes=t.enter,await e(),await i(20),this.$emit("enter"),this.transitionEnded=!1,this.$emit("afterEnter"),this.classes=t["enter-to"]},async vueLeave(){if(!this.display)return;const t=p(this.mode);this.status="leave",this.$emit("beforeLeave"),this.classes=t.leave,await e(),this.transitionEnded=!1,this.$emit("leave"),setTimeout(this.onTransitionEnd,this.duration),this.classes=t["leave-to"]},onTransitionEnd(){this.transitionEnded||(this.transitionEnded=!0,this.$emit("leave"===this.status?"afterLeave":"afterEnter"),!this.show&&this.display&&(this.display=!1,this.inited=!1))}}},f],watch:{show:{handler(t){t?this.vueEnter():this.vueLeave()},immediate:!0}}},[["render",function(t,e,i,s,a,n){const v=m;return a.inited?(o(),r(v,{key:0,class:u(["u-transition",a.classes]),ref:"u-transition",onClick:t.clickHandler,style:h([n.mergeStyle]),onTouchmove:t.noop},{default:l((()=>[d(t.$slots,"default",{},void 0,!0)])),_:3},8,["onClick","class","style","onTouchmove"])):c("",!0)}],["__scopeId","data-v-e2891139"]]);export{y as _};
