import{a0 as t,a1 as s,a2 as e,bC as i,al as o,a3 as n,a4 as c,bD as h,bE as d,bh as r,bF as a,bi as y,y as l,z as u,A as p,H as k,a9 as b,a8 as f,C as x}from"./index-DKPlMZwP.js";import{_ as m}from"./_plugin-vue_export-helper.BCo6x5W8.js";const S=m({name:"u-sticky",mixins:[s,e,{props:{offsetTop:{type:[String,Number],default:()=>t.sticky.offsetTop},customNavHeight:{type:[String,Number],default:44},disabled:{type:Boolean,default:()=>t.sticky.disabled},bgColor:{type:String,default:()=>t.sticky.bgColor},zIndex:{type:[String,Number],default:()=>t.sticky.zIndex},index:{type:[String,Number],default:()=>t.sticky.index}}}],data:()=>({cssSticky:!1,stickyTop:0,elId:i(),left:0,width:"auto",height:"auto",fixed:!1}),computed:{style(){const t={};return this.disabled?t.position="static":this.cssSticky?(t.position="sticky",t.zIndex=this.uZindex,t.top=o(this.stickyTop)):t.height=this.fixed?this.height+"px":"auto",t.backgroundColor=this.bgColor,n(c(this.customStyle),t)},stickyContent(){const t={};return this.cssSticky||(t.position=this.fixed?"fixed":"static",t.top=this.stickyTop+"px",t.left=this.left+"px",t.width="auto"==this.width?"auto":this.width+"px",t.zIndex=this.uZindex),t},uZindex(){return this.zIndex?this.zIndex:h.sticky}},mounted(){this.init()},methods:{init(){this.getStickyTop(),this.checkSupportCssSticky(),this.cssSticky||!this.disabled&&this.initObserveContent()},initObserveContent(){this.$uGetRect("#"+this.elId).then((t=>{this.height=t.height,this.left=t.left,this.width=t.width,this.$nextTick((()=>{this.observeContent()}))}))},observeContent(){this.disconnectObserver("contentObserver");const t=d({thresholds:[.95,.98,1]});t.relativeToViewport({top:-this.stickyTop}),t.observe(`#${this.elId}`,(t=>{this.setFixed(t.boundingClientRect.top)})),this.contentObserver=t},setFixed(t){const s=t<=this.stickyTop;this.fixed=s},disconnectObserver(t){const s=this[t];s&&s.disconnect()},getStickyTop(){this.stickyTop=r(this.offsetTop)+r(this.customNavHeight)},async checkSupportCssSticky(){this.checkCssStickyForH5()&&(this.cssSticky=!0),"android"===a()&&Number(y().system)>8&&(this.cssSticky=!0),"ios"===a()&&(this.cssSticky=!0)},checkComputedStyle(){},checkCssStickyForH5(){const t=["","-webkit-","-ms-","-moz-","-o-"],s=t.length,e=document.createElement("div");for(let i=0;i<s;i++)if(e.style.position=t[i]+"sticky",""!==e.style.position)return!0;return!1}},beforeUnmount(){this.disconnectObserver("contentObserver")}},[["render",function(t,s,e,i,o,n){const c=x;return l(),u(c,{class:"u-sticky",id:o.elId,style:b([n.style])},{default:p((()=>[k(c,{style:b([n.stickyContent]),class:"u-sticky__content"},{default:p((()=>[f(t.$slots,"default",{},void 0,!0)])),_:3},8,["style"])])),_:3},8,["id","style"])}],["__scopeId","data-v-057f8620"]]);export{S as _};
