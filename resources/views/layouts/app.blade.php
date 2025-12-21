@if(Module::has('Core') && Module::isEnabled('Core'))
  @extends('core::layouts.app')
  @break
@else
  @extends('usermanagement::layouts.master')
@endif