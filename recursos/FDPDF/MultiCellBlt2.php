<?php
require('fpdf.php');

class PDF extends FPDF
{
	/************************************************************
	*                                                           *
	*    MultiCell with bullet (array)                          *
	*                                                           *
	*    Requires an array with the following  keys:            *
	*                                                           *
	*        Bullet -> String or Number                         *
	*        Margin -> Number, space between bullet and text    *
	*        Indent -> Number, width from current x position    *
	*        Spacer -> Number, calls Cell(x), spacer=x          *
	*        Text -> Array, items to be bulleted	            *
	*                                                           *
	************************************************************/
	
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';

	function MultiCellBltArray($w, $h, $blt_array, $border=0, $align='J', $fill=false)
	{
		if (!is_array($blt_array))
		{
			die('MultiCellBltArray requires an array with the following keys: bullet,margin,text,indent,spacer');
			exit;
		}
				
		//Save x
		$bak_x = $this->x;
		
		for ($i=0; $i<sizeof($blt_array['text']); $i++)
		{
			//Get bullet width including margin
			$blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin'])+$this->cMargin*2;
			
			// SetX
			$this->SetX($bak_x);
			
			//Output indent
			if ($blt_array['indent'] > 0)
				$this->Cell($blt_array['indent']);
			
			//Output bullet
			$this->Cell($blt_width,$h,$blt_array['bullet'] . $blt_array['margin'],0,'',$fill);
			
			//Output text
			$this->MultiCell($w-$blt_width,$h,$blt_array['text'][$i],$border,$align,$fill);
			
			//Insert a spacer between items if not the last item
			if ($i != sizeof($blt_array['text'])-1)
				$this->Ln($blt_array['spacer']);
			
			//Increment bullet if it's a number
			if (is_numeric($blt_array['bullet']))
				$blt_array['bullet']++;
		}
	
		//Restore x
		$this->x = $bak_x;
	}
	
	function WriteHTML($html)
	{
		// Intérprete de HTML
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
					else
						$this->Write(5,$e);
			}
			else
			{
				// Etiqueta
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
					else
					{
						// Extraer atributos
						$a2 = explode(' ',$e);
						$tag = strtoupper(array_shift($a2));
						$attr = array();
						foreach($a2 as $v)
						{
							if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
								$attr[strtoupper($a3[1])] = $a3[2];
						}
						$this->OpenTag($tag,$attr);
					}
			}
		}
	}
	
	function OpenTag($tag, $attr)
	{
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
			if($tag=='A')
				$this->HREF = $attr['HREF'];
				if($tag=='BR')
					$this->Ln(5);
	}
	
	function CloseTag($tag)
	{
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
			if($tag=='A')
				$this->HREF = '';
	}
	
	function SetStyle($tag, $enable)
	{
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}
	
	function PutLink($URL, $txt)
	{
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
}
?>
