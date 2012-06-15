jQuery(document).ready(function(){
	jQuery('.desktop').outerWidth(jQuery(window).width());
	jQuery('.desktop').outerHeight(jQuery(window).height());
	jQuery(window).resize(function(){
		jQuery('.desktop').outerWidth(jQuery(window).width());
		jQuery('.desktop').outerHeight(jQuery(window).height());
	});
});
/*
function Point(x,y){
	this.x=x;
	this.y=y;
	this.equals=function(point){
		return (point.x==this.x&&point.y==this.y);
	};
}
function Icon(desktopManager,htmlElement){
	this.desktopManager=desktopManager;
	this.htmlElement=htmlElement;
	this.position=null;
	this.isDragged=false;
	this.dragPosition=null;

	var parent=this;
	this.htmlElement.mousedown(function(){
		parent.isDragged=true;
		parent.desktopManager.currentDragged=parent;
	});

	this.setToPosition=function(){
		var positionToUse=null;
		if (this.position!=null){
			positionToUse=this.position;
		}else {
			positionToUse=this.desktopManager.getNextPosition();
			this.position=positionToUse;
		}
		this.desktopManager.usedPositions.push(positionToUse);
		this.setToGivenPosition(positionToUse);
	};
	this.setToGivenPosition=function(positionToUse){
		this.htmlElement.css({top : positionToUse.y+'px',left : positionToUse.x+'px'});
	};
}
function DesktopManager(width,height){
	this.maxWidth=width;
	this.maxHeight=height;
	this.usedPositions=new Array();
	this.currentPosition=new Point(10,10);
	this.currentDragged=null;

	var parent=this;
	jQuery('.desktop .pif').mouseup(function(){
		if (parent.currentDragged.dragPosition!=null){
			parent.freePosition(parent.currentDragged.position);
			parent.currentDragged.position=parent.currentDragged.dragPosition;
			parent.currentDragged.dragPosition=null;
			parent.currentDragged.htmlElement.removeClass('dragged');
			parent.currentDragged.setToPosition();
		}
		parent.currentDragged.isDragged=false;
		parent.currentDragged=null;
	});

	jQuery('.desktop').mousemove(function(event){
		if (parent.currentDragged!=null&&parent.currentDragged.isDragged){
			event.preventDefault();
			parent.currentDragged.dragPosition=parent.findPosition(new Point(event.pageX,event.pageY));
			if (!parent.isPositionUsed(parent.currentDragged.dragPosition)||parent.currentDragged.dragPosition.equals(parent.currentDragged.position)){
				parent.currentDragged.htmlElement.addClass('dragged');
				parent.currentDragged.setToGivenPosition(parent.currentDragged.dragPosition);
			}else {
				parent.currentDragged.htmlElement.removeClass('dragged');
				parent.currentDragged.dragPosition=null;
			}
			return false;
		}
	});

	this.freePosition=function(position){
		var toReturn=new Array();
		for (var i in this.usedPositions){
			if (!this.usedPositions[i].equals(position)){
				toReturn.push(this.usedPositions[i]);
			}
		}
		this.usedPositions=toReturn;
	};

	this.getNextPosition=function(){
		while(this.isPositionUsed(this.currentPosition)){
			this.currentPosition=new Point(this.currentPosition.x,this.currentPosition.y+160);
			if (this.currentPosition.y+150>this.maxHeight){
				this.currentPosition.y=10;
				this.currentPosition.x+=130;
			}
		}
		return this.currentPosition;
	};
	this.isPositionUsed=function(position){
		for(var i in this.usedPositions){
			if (this.usedPositions[i].equals(position)){
				return true;
			}
		}
		return false;
	}
	this.findPosition=function(position){
		return new Point((Math.floor((position.x-10)/130))*130+10,(Math.floor((position.y-10)/160))*160+10);
	};
}*/
