@if(Module::has('Core') && Module::isEnabled('Core'))
  @extends('core::layouts.app')
@else
  @extends('usermanagement::layouts.master')
@endif