import{aB as t,b9 as e,ba as s,bb as i,b1 as h,bc as n,bd as a,y as o,z as c,A as r,aa as l,G as d,aC as u,C as f}from"./index-DHIuICwK.js";import{_ as m}from"./_plugin-vue_export-helper.BCo6x5W8.js";const v={},g=/([\d\.]+)px/;class p{constructor(){this.__events={}}on(t,e){if(!t||!e)return;const s=this.__events[t]||[];s.push(e),this.__events[t]=s}emit(t,e){if(t.constructor===Object&&(t=(e=t)&&e.type),!t)return;const s=this.__events[t];s&&s.length&&s.forEach((t=>{t.call(this,e)}))}off(t,e){const s=this.__events,i=s[t];if(i&&i.length)if(e)for(let h=0,n=i.length;h<n;h++)i[h]===e&&(i.splice(h,1),h--);else delete s[t]}}class w{constructor(){this.currentSrc=null,this.naturalHeight=0,this.naturalWidth=0,this.width=0,this.height=0,this.tagName="IMG"}set src(t){this.currentSrc=t,i({src:t,success:t=>{this.naturalWidth=this.width=t.width,this.naturalHeight=this.height=t.height,this.onload()},fail:()=>{this.onerror()}})}get src(){return this.currentSrc}}class y{constructor(t,e,s){this.tagName="canvas",this.com=e,this.canvasId=s,this.ctx=t}set width(t){this.com.offscreenWidth=t}set height(t){this.com.offscreenHeight=t}get width(){return this.com.offscreenWidth||0}get height(){return this.com.offscreenHeight||0}getContext(t){return this.ctx}getImageData(){return new Promise(((t,e)=>{this.com.$nextTick((()=>{s({x:0,y:0,width:this.com.offscreenWidth,height:this.com.offscreenHeight,canvasId:this.canvasId,success:e=>{t(e)},fail:t=>{e(t)}},this.com)}))}))}}class x{constructor(t,e,s,i={}){v[e.canvasId]={ctx:t},this.canvasId=e.canvasId,this.chart=null,this.isNew=s,this.tagName="canvas",this.canvasNode=i,this.com=e,s||this._initStyle(t),this._initEvent(),this._ee=new p}getContext(t){if("2d"===t)return this.ctx}setAttribute(t,e){"aria-label"===t&&(this.com.ariaLabel=e)}setChart(t){this.chart=t}createOffscreenCanvas(e){if(!this.children){this.com.isOffscreenCanvas=!0,this.com.offscreenWidth=e.width||300,this.com.offscreenHeight=e.height||300;const s=this.com,i=this.com.offscreenCanvasId,h=t(i,this.com);this._initStyle(h),this.children=new y(h,s,i)}return this.children}appendChild(t){console.log("child",t)}dispatchEvent(t,e){return"object"==typeof t?this._ee.emit(t.type,t):this._ee.emit(t,e),!0}attachEvent(){}detachEvent(){}addEventListener(t,e){this._ee.on(t,e)}removeEventListener(t,e){this._ee.off(t,e)}_initCanvas(t,e){}_initStyle(t,e){if(["fillStyle","strokeStyle","fontSize","globalAlpha","opacity","textAlign","textBaseline","shadow","lineWidth","lineCap","lineJoin","lineDash","miterLimit","font"].forEach((e=>{Object.defineProperty(t,e,{set:s=>{if("font"===e&&g.test(s)){const e=g.exec(s);t.setFontSize(e[1])}else if("opacity"!==e){if("fillStyle"!==e&&"strokeStyle"!==e||"none"!==s&&null!==s){if("object"==typeof s)return void((s.hasOwnProperty("colorStop")||s.hasOwnProperty("colors"))&&t["set"+e.charAt(0).toUpperCase()+e.slice(1)](s));t["set"+e.charAt(0).toUpperCase()+e.slice(1)](s)}}else t.setGlobalAlpha(s)}})})),this.isNew||e||(t.uniDrawImage=t.drawImage,t.drawImage=(...e)=>{e[0]=e[0].src,t.uniDrawImage(...e)}),t.createRadialGradient||(t.createRadialGradient=function(){return t.createCircularGradient(...[...arguments].slice(-3))}),t.strokeText||(t.strokeText=(...e)=>{t.fillText(...e)}),!t.measureText){const e=t=>{let e=0;for(let s=0;s<t.length;s++)t.charCodeAt(s)>0&&t.charCodeAt(s)<128?e++:e+=2;return e};t.measureText=(s,i)=>{var h;let n=(null==(h=null==t?void 0:t.state)?void 0:h.fontSize)||12;i&&(n=parseInt(i.match(/([\d\.]+)px/)[1])),n/=2;const a=n>=16?1.3:1;return{width:e(s)*n*a}}}}_initEvent(t){this.event={};[{wxName:"touchStart",ecName:"mousedown"},{wxName:"touchMove",ecName:"mousemove"},{wxName:"touchEnd",ecName:"mouseup"},{wxName:"touchEnd",ecName:"click"}].forEach((t=>{this.event[t.wxName]=e=>{const s=e.touches[0];this.chart.getZr().handler.dispatch(t.ecName,{zrX:"tap"===t.wxName?s.clientX:s.x,zrY:"tap"===t.wxName?s.clientY:s.y})}}))}set width(t){this.canvasNode.width=t}set height(t){this.canvasNode.height=t}get width(){return this.canvasNode.width||0}get height(){return this.canvasNode.height||0}get ctx(){return v[this.canvasId].ctx||null}set chart(t){v[this.canvasId].chart=t}get chart(){return v[this.canvasId].chart||null}}function b(t,{x:e,y:s,wheelDelta:i}){this.dispatch(t,{zrX:e,zrY:s,zrDelta:i,preventDefault:()=>{},stopPropagation:()=>{}})}function C(t){return Array.isArray(t)?t:"object"==typeof t&&null!==t?Object.values(t):t}function T(t){for(let e=0;e<t.touches.length;++e){const s=t.touches[e];s.offsetX=s.x,s.offsetY=s.y}return t}h();const _=h().pixelRatio;function I(t,e={}){const s="boundingClientRect",{context:i,type:h=s}=e;return new Promise(((e,a)=>{const o=n().in(i).select(t),c=t=>{t?e(t):a()};h==s?o[h](c).exec():o[h]({node:!0,size:!0,rect:!0},c).exec()}))}const S=m({name:"lime-echart",props:{customStyle:String,isDisableScroll:Boolean,isClickable:{type:Boolean,default:!0},enableHover:Boolean,beforeDelay:{type:Number,default:30}},data:()=>({use2dCanvas:!1,ariaLabel:"图表",width:null,height:null,nodeWidth:null,nodeHeight:null,config:{},inited:!1,finished:!1,file:"",platform:"",isPC:!1,isDown:!1,isOffscreenCanvas:!1,offscreenWidth:0,offscreenHeight:0}),computed:{canvasId(){return`lime-echart${this._&&this._.uid||this._uid}`},offscreenCanvasId(){return`${this.canvasId}_offscreen`},offscreenStyle(){return`width:${this.offscreenWidth}px;height: ${this.offscreenHeight}px; position: fixed; left: 99999px; background: red`},canvasStyle(){return this.width&&this.height?"width:"+this.width+"px;height:"+this.height+"px":""}},beforeUnmount(){this.clear(),this.dispose(),this.isPC&&document.removeEventListener("mousewheel",this.mousewheel)},created(){"ontouchstart"in window||(this.isPC=!0,document.addEventListener("mousewheel",this.mousewheel)),this.use2dCanvas="2d"===this.type&&!1},mounted(){this.$nextTick((()=>{this.$emit("finished")}))},methods:{setChart(t){this.chart?"function"==typeof t&&this.chart&&t(this.chart):console.warn("组件还未初始化，请先使用 init")},setOption(){this.chart&&this.chart.setOption?this.chart.setOption(...arguments):console.warn("组件还未初始化，请先使用 init")},showLoading(){this.chart&&this.chart.showLoading(...arguments)},hideLoading(){this.chart&&this.chart.hideLoading()},clear(){this.chart&&this.chart.clear()},dispose(){this.chart&&this.chart.dispose()},resize(t){t&&t.width&&t.height?(this.height=t.height,this.width=t.width,this.chart&&this.chart.resize(t)):this.$nextTick((()=>{n().in(this).select(".lime-echart").boundingClientRect().exec((t=>{if(t){let{width:e,height:s}=t[0];this.width=e=e||300,this.height=s=s||300,this.chart.resize({width:e,height:s})}}))}))},canvasToTempFilePath(t={}){const{use2dCanvas:e,canvasId:s}=this;return new Promise(((i,h)=>{const n=Object.assign({canvasId:s,success:i,fail:h},t);e&&(delete n.canvasId,n.canvas=this.canvasNode),a(n,this)}))},async init(t,...s){if(s&&0==s.length&&!t)return void console.error("缺少参数：init(echarts, theme?:string, opts?: object, callback?: function)");let i,h=null,n={};var a;Array.from(arguments).forEach((t=>{"function"==typeof t&&(i=t),["string"].includes(typeof t)&&(h=t),"object"==typeof t&&(n=t)})),this.beforeDelay&&await(a=this.beforeDelay,new Promise((t=>{setTimeout((()=>{t(!0)}),a)})));let o=await this.getContext();if(function(t,{canvas:s,node:i}){if(t&&!t.registerPreprocessor)return console.warn("echarts 版本不对或未传入echarts，vue3请使用esm格式");t.registerPreprocessor((t=>{t&&t.series&&(t.series.length>0?t.series.forEach((t=>{t.progressive=0})):"object"==typeof t.series&&(t.series.progressive=0))})),t.setPlatformAPI&&t.setPlatformAPI({loadImage:s.setChart?function(t,e,s){let h=null;return i&&i.createImage?(h=i.createImage(),h.onload=e.bind(h),h.onerror=s.bind(h),h.src=t,h):(h=new w,h.onload=e.bind(h),h.onerror=s.bind(h),h.src=t,h)}:null,createCanvas(){const t="createOffscreenCanvas";return e(t)&&uni[t]?uni[t]({type:"2d"}):s}})}(t,o),this.chart=t.init(o.canvas,h,Object.assign({},o,n)),"function"!=typeof i)return this.chart;i(this.chart)},getContext(){return I(`#${this.canvasId}`,{context:this,type:this.use2dCanvas?"fields":"boundingClientRect"}).then((e=>{if(e){let s,i=_,{width:h,height:n,node:a}=e;if(this.width=h=h||300,this.height=n=n||300,a){const t=a.getContext("2d");s=new x(t,this,!0,a),this.canvasNode=a}else{i=this.isPC?_:1,this.rect=e,this.nodeWidth=h*i,this.nodeHeight=n*i;const a=t(this.canvasId,this);s=new x(a,this,!1)}return{canvas:s,width:h,height:n,devicePixelRatio:i,node:a}}return{}}))},getRelative(t,e){let{clientX:s,clientY:i}=t;return s&&i||!e||!e[0]||(s=e[0].clientX,i=e[0].clientY),{x:s-this.rect.left,y:i-this.rect.top,wheelDelta:t.wheelDelta||0}},getTouch(t,e){const{x:s}=e&&e[0]||{};return s?e[0]:this.getRelative(t,e)},touchStart(t){this.isDown=!0;const e=()=>{const e=C(t.touches);if(this.chart){const s=this.getTouch(t,e);this.startX=s.x,this.startY=s.y,this.startT=new Date;const i=this.chart.getZr().handler;b.call(i,"mousedown",s),b.call(i,"mousemove",s),i.processGesture(T(t),"start"),clearTimeout(this.endTimer)}};this.isPC?I(`#${this.canvasId}`,{context:this}).then((t=>{this.rect=t,e()})):e()},touchMove(t){this.isPC&&this.enableHover&&!this.isDown&&(this.isDown=!0);const e=C(t.touches);if(this.chart&&this.isDown){const s=this.chart.getZr().handler;b.call(s,"mousemove",this.getTouch(t,e)),s.processGesture(T(t),"change")}},touchEnd(t){if(this.isDown=!1,this.chart){const e=C(t.changedTouches),{x:s}=e&&e[0]||{},i=(s?e[0]:this.getRelative(t,e))||{},h=this.chart.getZr().handler,n=Math.abs(i.x-this.startX)<10&&new Date-this.startT<200;b.call(h,"mouseup",i),h.processGesture(T(t),"end"),n?b.call(h,"click",i):this.endTimer=setTimeout((()=>{b.call(h,"mousemove",{x:999999999,y:999999999}),b.call(h,"mouseup",{x:999999999,y:999999999})}),50)}},mousewheel(t){this.chart&&b.call(this.chart.getZr().handler,"mousewheel",this.getTouch(t))}}},[["render",function(t,e,s,i,h,n){const a=u,m=f;return n.canvasId?(o(),c(m,{key:0,class:"lime-echart",style:l(s.customStyle),ref:"limeEchart","aria-label":h.ariaLabel},{default:r((()=>[h.use2dCanvas?(o(),c(a,{key:0,class:"lime-echart__canvas",type:"2d",id:n.canvasId,style:l(n.canvasStyle),"disable-scroll":s.isDisableScroll,onTouchstart:n.touchStart,onTouchmove:n.touchMove,onTouchend:n.touchEnd},null,8,["id","style","disable-scroll","onTouchstart","onTouchmove","onTouchend"])):(o(),c(a,{key:1,class:"lime-echart__canvas",width:h.nodeWidth,height:h.nodeHeight,style:l(n.canvasStyle),"canvas-id":n.canvasId,id:n.canvasId,"disable-scroll":s.isDisableScroll,onTouchstart:n.touchStart,onTouchmove:n.touchMove,onTouchend:n.touchEnd},null,8,["width","height","style","canvas-id","id","disable-scroll","onTouchstart","onTouchmove","onTouchend"])),h.isPC?(o(),c(m,{key:2,class:"lime-echart__mask",onMousedown:n.touchStart,onMousemove:n.touchMove,onMouseup:n.touchEnd,onTouchstart:n.touchStart,onTouchmove:n.touchMove,onTouchend:n.touchEnd},null,8,["onMousedown","onMousemove","onMouseup","onTouchstart","onTouchmove","onTouchend"])):d("",!0),h.isOffscreenCanvas?(o(),c(a,{key:3,style:l(n.offscreenStyle),"canvas-id":n.offscreenCanvasId},null,8,["style","canvas-id"])):d("",!0)])),_:1},8,["style","aria-label"])):d("",!0)}],["__scopeId","data-v-00a1d4e6"]]);export{S as _};
