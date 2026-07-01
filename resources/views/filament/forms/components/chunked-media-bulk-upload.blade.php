@php($u=route('filament.admin.media.upload.chunk'))
@php($f=route('filament.admin.media.upload.finalize'))
@php($d=$uploadDirectory??'media')
<div x-data="bm(@js($u),@js($f),@js($d),@js(csrf_token()))" wire:ignore class="chunked-media-upload">
<button type="button" class="chunked-media-upload__dropzone" :class="{'is-dragging':drag}" @click="pick()" @dragover.prevent="drag=true" @dragleave.prevent="drag=false" @drop.prevent="drag=false;if($event.dataTransfer.files.length)add($event.dataTransfer.files)">
<div class="chunked-media-upload__content"><p class="chunked-media-upload__hint">Drag &amp; drop files or <span class="chunked-media-upload__browse">browse</span> to bulk upload</p></div>
</button>
<p class="chunked-media-upload__status" x-text="status"></p>
<script>
function bm(u,f,d,c){return{drag:false,status:'',q:[],run:false,s:10485760,
pick(){let i=document.createElement('input');i.type='file';i.multiple=1;i.style.display='none';document.body.appendChild(i);i.onchange=()=>{this.add(i.files);i.remove()};i.click()},
add(fs){for(let x of Array.from(fs||[]))this.q.push(x);if(!this.run)this.next()},
async next(){this.run=true;while(this.q.length)await this.one(this.q.shift());this.run=false;this.status='Done'},
async one(x){let id=Date.now()+'-'+Math.random().toString(36).slice(2,8),t=Math.ceil(x.size/this.s);for(let k=0;k<t;k++){let b=x.slice(k*this.s,Math.min((k+1)*this.s,x.size)),fd=new FormData();fd.append('_token',c);fd.append('upload_id',id);fd.append('chunk_index',k);fd.append('total_chunks',t);fd.append('original_name',x.name);fd.append('chunk',b,x.name+'.part');this.status='Uploading '+x.name+' ('+(k+1)+'/'+t+')';let r=await fetch(u,{method:'POST',credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body:fd});if(!r.ok){this.status='Failed '+x.name;return}};
let z=await fetch(f,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':c,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body:JSON.stringify({upload_id:id,total_chunks:t,original_name:x.name,directory:d})});if(!z.ok){this.status='Finalize failed '+x.name;return};this.status='Uploaded '+x.name}
}}
</script>
</div>
