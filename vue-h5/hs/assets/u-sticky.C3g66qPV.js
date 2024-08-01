import{a1 as t,a2 as s,a3 as e,b2 as i,al as o,a4 as n,a5 as c,b3 as h,b4 as d,aU as a,b5 as r,aV as y,y as l,z as u,A as p,H as k,aa as f,a9 as b,C as x}from"./index-Bbq3x6Nx.js";import{_ as m}from"./_plugin-vue_export-helper.BCo6x5W8.js";const S=m({name:"u-sticky",mixins:[s,e,{props:{offsetTop:{type:[String,Number],default:()=>t.sticky.offsetTop},customNavHeight:{type:[String,Number],default:44},disabled:{type:Boolean,default:()=>t.sticky.disabled},bgColor:{type:String,default:()=>t.sticky.bgColor},zIndex:{type:[String,Number],default:()=>t.sticky.zIndex},index:{type:[String,Number],default:()=>t.sticky.index}}}],data:()=>({cssSticky:!1,stickyTop:0,elId:i(),left:0,width:"auto",height:"auto",fixed:!1}),computed:{style(){const t={};return this.disabled?t.position="static":this.cssSticky?(t.position="sticky",t.zIndex=this.uZindex,t.top=o(this.stickyTop)):t.height=this.fixed?this.height+"px":"auto",t.backgroundColor=this.bgColor,n(c(this.customStyle),t)},stickyContent(){const t={};return this.cssSticky||(t.position=this.fixed?"fixed":"static",t.top=this.stickyTop+"px",t.left=this.left+"px",t.width="auto"==this.width?"auto":this.width+"px",t.zIndex=this.uZindex),t},uZindex(){return this.zIndex?this.zIndex:h.sticky}},mounted(){this.init()},methods:{init(){this.getStickyTop(),this.checkSupportCssSticky(),this.cssSticky||!this.disabled&&this.initObserveContent()},initObserveContent(){this.$uGetRect("#"+this.elId).then((t=>{this.height=t.height,this.left=t.left,this.width=t.width,this.$nextTick((()=>{this.observeContent()}))}))},observeContent(){this.disconnectObserver("contentObserver");const t=d({thresholds:[.95,.98,1]});t.relativeToViewport({top:-this.stickyTop}),t.observe(`#${this.elId}`,(t=>{this.setFixed(t.boundingClientRect.top)})),this.contentObserver=t},setFixed(t){const s=t<=this.stickyTop;this.fixed=s},disconnectObserver(t){const s=this[t];s&&s.disconnect()},getStickyTop(){this.stickyTop=a(this.offsetTop)+a(this.customNavHeight)},async checkSupportCssSticky(){this.checkCssStickyForH5()&&(this.cssSticky=!0),"android"===r()&&Number(y().system)>8&&(this.cssSticky=!0),"ios"===r()&&(this.cssSticky=!0)},checkComputedStyle(){},checkCssStickyForH5(){const t=["","-webkit-","-ms-","-moz-","-o-"],s=t.length,e=document.createElement("div");for(let i=0;i<s;i++)if(e.style.position=t[i]+"sticky",""!==e.style.position)return!0;return!1}},beforeUnmount(){this.disconnectObserver("contentObserver")}},[["render",function(t,s,e,i,o,n){const c=x;return l(),u(c,{class:"u-sticky",id:o.elId,style:f([n.style])},{default:p((()=>[k(c,{style:f([n.stickyContent]),class:"u-sticky__content"},{default:p((()=>[b(t.$slots,"default",{},void 0,!0)])),_:3},8,["style"])])),_:3},8,["id","style"])}],["__scopeId","data-v-057f8620"]]);export{S as _};
