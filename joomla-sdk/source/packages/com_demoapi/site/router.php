<?php
function DemoapiBuildRoute( &$query )
{
       $segments = array();
       if(isset($query['view']))
       {
                $segments[] = $query['view'];
                unset( $query['view'] );
       }
       return $segments;
}

function DemoapiParseRoute( $segments )
{
       $vars = array();
       switch($segments[0])
       {
               case 'thankyou':
                       $vars['view'] = 'thankyou';
                       break;       
               case 'activation':
                       $vars['view'] = 'activation';
                       break;
       }
       return $vars;
}