import{R as t,T as e,U as n,V as r,x as s,y as a,z as i,A as u,W as o,X as c,C as l,H as d,Y as f,Z as h,I as $,J as m,D as p,E as y,F as g,B as v,G as _,q as M,N as D}from"./index-MyvEfzqB.js";import{_ as b}from"./uni-nav-bar.C6dUwUWD.js";import{r as w}from"./uni-app.es.OJn_m3UG.js";import{_ as S}from"./u-image.yMmSwPAo.js";import{g as k,b as x}from"./index.CndE9odl.js";import{_ as O}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DKHtcsXs.js";import"./uni-status-bar.BbIxW0bn.js";import"./u-icon.B3OJ4aDB.js";import"./u-transition.Ctc1gPNf.js";const j=""+new URL("dou-D0lEhvfN.png",import.meta.url).href;"undefined"!=typeof globalThis?globalThis:"undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self&&self;function C(t){return t&&t.__esModule&&Object.prototype.hasOwnProperty.call(t,"default")?t.default:t}var Y={exports:{}};const I=C(Y.exports=function(){var t=1e3,e=6e4,n=36e5,r="millisecond",s="second",a="minute",i="hour",u="day",o="week",c="month",l="quarter",d="year",f="date",h="Invalid Date",$=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,m=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,p={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(t){var e=["th","st","nd","rd"],n=t%100;return"["+t+(e[(n-20)%10]||e[n]||e[0])+"]"}},y=function(t,e,n){var r=String(t);return!r||r.length>=e?t:""+Array(e+1-r.length).join(n)+t},g={s:y,z:function(t){var e=-t.utcOffset(),n=Math.abs(e),r=Math.floor(n/60),s=n%60;return(e<=0?"+":"-")+y(r,2,"0")+":"+y(s,2,"0")},m:function t(e,n){if(e.date()<n.date())return-t(n,e);var r=12*(n.year()-e.year())+(n.month()-e.month()),s=e.clone().add(r,c),a=n-s<0,i=e.clone().add(r+(a?-1:1),c);return+(-(r+(n-s)/(a?s-i:i-s))||0)},a:function(t){return t<0?Math.ceil(t)||0:Math.floor(t)},p:function(t){return{M:c,y:d,w:o,d:u,D:f,h:i,m:a,s:s,ms:r,Q:l}[t]||String(t||"").toLowerCase().replace(/s$/,"")},u:function(t){return void 0===t}},v="en",_={};_[v]=p;var M="$isDayjsObject",D=function(t){return t instanceof k||!(!t||!t[M])},b=function t(e,n,r){var s;if(!e)return v;if("string"==typeof e){var a=e.toLowerCase();_[a]&&(s=a),n&&(_[a]=n,s=a);var i=e.split("-");if(!s&&i.length>1)return t(i[0])}else{var u=e.name;_[u]=e,s=u}return!r&&s&&(v=s),s||!r&&v},w=function(t,e){if(D(t))return t.clone();var n="object"==typeof e?e:{};return n.date=t,n.args=arguments,new k(n)},S=g;S.l=b,S.i=D,S.w=function(t,e){return w(t,{locale:e.$L,utc:e.$u,x:e.$x,$offset:e.$offset})};var k=function(){function p(t){this.$L=b(t.locale,null,!0),this.parse(t),this.$x=this.$x||t.x||{},this[M]=!0}var y=p.prototype;return y.parse=function(t){this.$d=function(t){var e=t.date,n=t.utc;if(null===e)return new Date(NaN);if(S.u(e))return new Date;if(e instanceof Date)return new Date(e);if("string"==typeof e&&!/Z$/i.test(e)){var r=e.match($);if(r){var s=r[2]-1||0,a=(r[7]||"0").substring(0,3);return n?new Date(Date.UTC(r[1],s,r[3]||1,r[4]||0,r[5]||0,r[6]||0,a)):new Date(r[1],s,r[3]||1,r[4]||0,r[5]||0,r[6]||0,a)}}return new Date(e)}(t),this.init()},y.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},y.$utils=function(){return S},y.isValid=function(){return!(this.$d.toString()===h)},y.isSame=function(t,e){var n=w(t);return this.startOf(e)<=n&&n<=this.endOf(e)},y.isAfter=function(t,e){return w(t)<this.startOf(e)},y.isBefore=function(t,e){return this.endOf(e)<w(t)},y.$g=function(t,e,n){return S.u(t)?this[e]:this.set(n,t)},y.unix=function(){return Math.floor(this.valueOf()/1e3)},y.valueOf=function(){return this.$d.getTime()},y.startOf=function(t,e){var n=this,r=!!S.u(e)||e,l=S.p(t),h=function(t,e){var s=S.w(n.$u?Date.UTC(n.$y,e,t):new Date(n.$y,e,t),n);return r?s:s.endOf(u)},$=function(t,e){return S.w(n.toDate()[t].apply(n.toDate("s"),(r?[0,0,0,0]:[23,59,59,999]).slice(e)),n)},m=this.$W,p=this.$M,y=this.$D,g="set"+(this.$u?"UTC":"");switch(l){case d:return r?h(1,0):h(31,11);case c:return r?h(1,p):h(0,p+1);case o:var v=this.$locale().weekStart||0,_=(m<v?m+7:m)-v;return h(r?y-_:y+(6-_),p);case u:case f:return $(g+"Hours",0);case i:return $(g+"Minutes",1);case a:return $(g+"Seconds",2);case s:return $(g+"Milliseconds",3);default:return this.clone()}},y.endOf=function(t){return this.startOf(t,!1)},y.$set=function(t,e){var n,o=S.p(t),l="set"+(this.$u?"UTC":""),h=(n={},n[u]=l+"Date",n[f]=l+"Date",n[c]=l+"Month",n[d]=l+"FullYear",n[i]=l+"Hours",n[a]=l+"Minutes",n[s]=l+"Seconds",n[r]=l+"Milliseconds",n)[o],$=o===u?this.$D+(e-this.$W):e;if(o===c||o===d){var m=this.clone().set(f,1);m.$d[h]($),m.init(),this.$d=m.set(f,Math.min(this.$D,m.daysInMonth())).$d}else h&&this.$d[h]($);return this.init(),this},y.set=function(t,e){return this.clone().$set(t,e)},y.get=function(t){return this[S.p(t)]()},y.add=function(r,l){var f,h=this;r=Number(r);var $=S.p(l),m=function(t){var e=w(h);return S.w(e.date(e.date()+Math.round(t*r)),h)};if($===c)return this.set(c,this.$M+r);if($===d)return this.set(d,this.$y+r);if($===u)return m(1);if($===o)return m(7);var p=(f={},f[a]=e,f[i]=n,f[s]=t,f)[$]||1,y=this.$d.getTime()+r*p;return S.w(y,this)},y.subtract=function(t,e){return this.add(-1*t,e)},y.format=function(t){var e=this,n=this.$locale();if(!this.isValid())return n.invalidDate||h;var r=t||"YYYY-MM-DDTHH:mm:ssZ",s=S.z(this),a=this.$H,i=this.$m,u=this.$M,o=n.weekdays,c=n.months,l=n.meridiem,d=function(t,n,s,a){return t&&(t[n]||t(e,r))||s[n].slice(0,a)},f=function(t){return S.s(a%12||12,t,"0")},$=l||function(t,e,n){var r=t<12?"AM":"PM";return n?r.toLowerCase():r};return r.replace(m,(function(t,r){return r||function(t){switch(t){case"YY":return String(e.$y).slice(-2);case"YYYY":return S.s(e.$y,4,"0");case"M":return u+1;case"MM":return S.s(u+1,2,"0");case"MMM":return d(n.monthsShort,u,c,3);case"MMMM":return d(c,u);case"D":return e.$D;case"DD":return S.s(e.$D,2,"0");case"d":return String(e.$W);case"dd":return d(n.weekdaysMin,e.$W,o,2);case"ddd":return d(n.weekdaysShort,e.$W,o,3);case"dddd":return o[e.$W];case"H":return String(a);case"HH":return S.s(a,2,"0");case"h":return f(1);case"hh":return f(2);case"a":return $(a,i,!0);case"A":return $(a,i,!1);case"m":return String(i);case"mm":return S.s(i,2,"0");case"s":return String(e.$s);case"ss":return S.s(e.$s,2,"0");case"SSS":return S.s(e.$ms,3,"0");case"Z":return s}return null}(t)||s.replace(":","")}))},y.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},y.diff=function(r,f,h){var $,m=this,p=S.p(f),y=w(r),g=(y.utcOffset()-this.utcOffset())*e,v=this-y,_=function(){return S.m(m,y)};switch(p){case d:$=_()/12;break;case c:$=_();break;case l:$=_()/3;break;case o:$=(v-g)/6048e5;break;case u:$=(v-g)/864e5;break;case i:$=v/n;break;case a:$=v/e;break;case s:$=v/t;break;default:$=v}return h?$:S.a($)},y.daysInMonth=function(){return this.endOf(c).$D},y.$locale=function(){return _[this.$L]},y.locale=function(t,e){if(!t)return this.$L;var n=this.clone(),r=b(t,e,!0);return r&&(n.$L=r),n},y.clone=function(){return S.w(this.$d,this)},y.toDate=function(){return new Date(this.valueOf())},y.toJSON=function(){return this.isValid()?this.toISOString():null},y.toISOString=function(){return this.$d.toISOString()},y.toString=function(){return this.$d.toUTCString()},p}(),x=k.prototype;return w.prototype=x,[["$ms",r],["$s",s],["$m",a],["$H",i],["$W",u],["$M",c],["$y",d],["$D",f]].forEach((function(t){x[t[1]]=function(e){return this.$g(e,t[0],t[1])}})),w.extend=function(t,e){return t.$i||(t(e,k,w),t.$i=!0),w},w.locale=b,w.isDayjs=D,w.unix=function(t){return w(1e3*t)},w.en=_[v],w.Ls=_,w.p={},w}()),T=O(Object.assign({name:"index-check-in-center"},{__name:"index-check-in-center",setup(O){const{t:C}=t(),Y=e(),T=I().format("DD"),H=n({datalist:[],jsondate:[]}),L=n({OA:"APP",token:Y.token,lang:"zh-cn"}),A=r(!0),W=r((t=>{const e=I(t).startOf("month"),n=I(t).endOf("month");let r=[],s=e;for(;s.isBefore(n);)r.push({day:s,isShow:!0}),s=s.add(1,"day");let a=r[0].day.day();a=7;for(let u=1;u<7;u++)r.unshift({day:e.add(-u,"day"),isShow:!1});let i=42-(I(t).daysInMonth()+7-1);for(let u=1;u<=i;u++)r.push({day:n.add(u,"day"),isMonth:!1,isShow:!1});return r.forEach((t=>{let e=t.day.$d.toLocaleDateString().replaceAll("/","-");t.time=I(e).format("D")})),r})(I())),N=r(I().format("M")),U=r(I().format("YYYY")),F=()=>{o({title:C("common.text.requesting")}),k(L).then((t=>{var e,n,r;t&&!t.error&&(H.datalist=(null==(e=null==t?void 0:t.data)?void 0:e.datalist)??[],H.jsondate=(null==(r=null==(n=null==t?void 0:t.data)?void 0:n.jsondate)?void 0:r.map((t=>t.signDay.replace(/^0/,""))))??[],A.value=!H.jsondate.includes(T))})).finally((()=>{c()}))};F();return(t,e)=>{const n=w(s("uni-nav-bar"),b),r=w(s("up-image"),S),k=l,O=D;return a(),i(k,{class:"check-in-center-page"},{default:u((()=>[d(n,{title:t.$t("page.title.checkInCenter"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:f(h)},null,8,["title","onClickLeft"]),d(r,{width:"100%",src:"/static/images/qdbanner.jpeg"}),d(k,{class:"calendar-box"},{default:u((()=>[d(k,{class:"calendar-header"},{default:u((()=>[$(m(U.value)+m(t.$t("common.text.year"))+m(N.value)+m(t.$t("common.text.month")),1)])),_:1}),d(k,{class:"list-box"},{default:u((()=>[(a(!0),p(g,null,y(W.value,((t,e)=>(a(),i(k,{key:e},{default:u((()=>[t.isShow?(a(),i(k,{key:0,class:v([H.jsondate.includes(t.time)?"on":""])},{default:u((()=>[$(m(t.time),1)])),_:2},1032,["class"])):_("",!0)])),_:2},1024)))),128))])),_:1})])),_:1}),d(k,{class:"check-btn-box"},{default:u((()=>[d(k,{class:v(["btn",A.value?"":"on"]),onClick:e[0]||(e[0]=()=>{(()=>{if(!A.value)return!1;o({title:C("common.text.loading")}),x({token:L.token,lang:L.lang}).then((t=>{t.error?M({title:t.message||C("home.text.interfaceError"),icon:"none"}):M({title:C("checkInCenter.text.tip"),icon:"none"}).then((()=>{F()}))})).finally((()=>{c()}))})()})},{default:u((()=>[$(m(t.$t("checkInCenter.text.signInNow")),1)])),_:1},8,["class"])])),_:1}),H.datalist.length?(a(),i(k,{key:0,class:"check-list-box"},{default:u((()=>[d(k,{class:"list-header"},{default:u((()=>[$(m(t.$t("checkInCenter.text.signInRecord")),1)])),_:1}),d(k,{class:"list-box"},{default:u((()=>[(a(!0),p(g,null,y(H.datalist,((e,n)=>(a(),i(k,{class:"list",key:n},{default:u((()=>[d(k,null,{default:u((()=>[d(O,{src:j,style:{width:"60upx",height:"60upx"}}),d(k,{class:"infos"},{default:u((()=>[d(k,null,{default:u((()=>[$(m(e.addtime)+m(t.$t("checkInCenter.text.tip2"))+m(e.count)+m(t.$t("common.text.day")),1)])),_:2},1024),d(k,null,{default:u((()=>[$(m(t.$t("checkInCenter.text.tip3")),1)])),_:1})])),_:2},1024)])),_:2},1024),d(k,{class:"btn"},{default:u((()=>[$(m(e.money)+m(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1})):_("",!0)])),_:1})}}}),[["__scopeId","data-v-04686a34"]]);export{T as default};
