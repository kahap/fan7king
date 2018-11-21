<?
class Img
{	
	var $picture_name;
	var $image_path;
	/** 圖片上傳資料夾 
	
	**/
	public function ImgUpdate($data,$image_path,$old_picture = null){
		if($data['name'] != null)
		{
			$this->picture_name = time().$data['name'];
			move_uploaded_file($data['tmp_name'],iconv("utf-8","big5",$image_path.$this->picture_name));	
		}
		else
		{
			$this->picture_name = $old_picture;
		}
		return $this->picture_name;
	}	
}
?>