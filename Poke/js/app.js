"use strict";

var app = angular.module("lection", ["ngRoute", "ngResource"]);

app.config(function($routeProvider, $logProvider) {
    $logProvider.debugEnabled(true);
    $routeProvider
    .when("/page/:id", {
        templateUrl : function(page){
        	return "assets/page-"+page.id+".html"
        },
        controller: "pagesController"
    })
    .otherwise("/page/0");
    
})
.controller("pagesController",function($scope,$log,$rootScope,$routeParams,$interval, $http){
	$scope.page=parseInt($routeParams.id) || 0;
 	$scope.users=null;
    $scope.names=null;

 	$http.get("?controller=user")
    .success(function(response) {
    	$scope.users = response;
    });

    $http.get("?controller=menu")
    .success(function(response) {
    	$scope.names = response;
    });

})
.controller('gameController',function($scope,$log,$rootScope,$routeParams,$interval, $timeout,$http){

    $scope.pokemons=null;   
    $scope.currentLevel=1;
    $scope.end=false;
    $scope.health=100;
    $scope.score=0;
    $scope.counter = 30;
    $scope.startButtonVisible=true;
    var mytimeout = null;
    var score=null; 

    $http.get("?controller=pokemon")
    .success(function(response) {
        $scope.pokemons = response;
    });

    $scope.start = function(){
        //$log.debug("start");
      $scope.$broadcast('startMove', $scope.pokemons[$scope.currentLevel-1]);
    };

    $scope.stop = function(){
      $scope.$broadcast('stopMove',{});
    };

    $scope.startGame=function(){
        //$log.debug("startGame");
        if($scope.currentLevel <= $scope.pokemons.length){
            alert("Level "+ $scope.currentLevel);
            $scope.start();
            $scope.currentLevel++;
            $scope.end=false;
        } else {
            $scope.end=true;
            $scope.stopTimer();
        }
    };

    $scope.postUser=function(){
        //$log.debug($scope.score);
        var person = prompt("Game over! Enter your name here, please", "Your name");
        $scope.countUsers=0;

        if(person!=null){
            $http.get("?controller=user")
            .success(function(response) {
                $scope.countUsers = response.length;

                $http.post('?controller=user',
                    {id: String($scope.countUsers), name: String(person), score: parseInt(score)})
                    .success(function(data, status, headers, config, url){
                        $http.get("?controller=user")
                        .success(function(response) {
                            $scope.users = response;
                        });
                    });
            });
        }
    };

    $scope.onTimeout = function() {
        //$log.debug("onTimeout");
        if($scope.counter ===  0) { //если на таймере 0
            if($scope.score==0){//если 0 очков
                $scope.startButtonVisible=true;
                //$scope.unblock('startButton');
                $scope.stopTimer();
                return;
            }
            else {//если очков больше, чем 0
                if($scope.end){//если это конец и уровней больше нет
                    //$scope.unblock('startButton');
                    $scope.startButtonVisible=true;
                    $scope.postUser();
                    $scope.stop();
                    return;
                }
                else{//если остались не пройденные уровни
                    //$log.debug("onTimeout level 2");
                    $scope.end=false;
                    $scope.$broadcast('timer-stopped', $scope.counter);
                    $scope.counter = 30;
                    $timeout.cancel(mytimeout);

                    $scope.startTimer();//запускаем счетчик заново
                    return;
                }
            }
        }
        $scope.counter--;
        mytimeout = $timeout($scope.onTimeout, 1000);
    };

    $scope.startTimer = function() {
        if(!$scope.end){//если не все уровни пройдены или не нажата кнопка finish
            //$log.debug("startTimer");
            $scope.startButtonVisible=false;
            //$scope.block('startButton');
            $scope.health=100;
            mytimeout = $timeout($scope.onTimeout, 1000);
            $scope.startGame();
        }
        else {
            $scope.stopTimer();
        }
    };

    $scope.stopTimer = function() {  
        if($scope.score==0){//если 0 очков
            alert("You lose");
        }
        else {
            score=parseInt($scope.score);
            $scope.postUser();
        }
        $scope.startButtonVisible=true;
        //$scope.unblock('startButton');
        $scope.stop();
        $scope.currentLevel=1;
        $scope.end=false;
        $scope.health=100;
        $scope.score=0;
        $scope.$broadcast('timer-stopped', $scope.counter);
        $scope.counter = 30;
        $timeout.cancel(mytimeout);
    };

    $scope.changeScore=function($power){

        if($scope.health==0){
            $scope.stopTimer();
        }
        else {
            if($scope.counter>20){
                $scope.health--;
                $scope.score=Math.round($scope.score + $power*(3+30-$scope.counter)*0.01);
            }
            else
            {
                if($scope.counter>10){
                    $scope.health=$scope.health-3;
                    $scope.score=Math.round($scope.score + $power*(3+30-$scope.counter)*0.02);
                }
                else {
                    $scope.health=$scope.health-7;
                    $scope.score=Math.round($scope.score + $power*(3+30-$scope.counter)*0.03);
                }
            }
        }
    };

    $scope.$on('changeScore',function(event, data){
       $scope.changeScore(data);
       //$log.debug(data);
     });

    $scope.toInt=function($string){
        return parseInt($string);
    }
})
.directive("menu", function(){

        return {
            templateUrl:'assets/directives/menu.html',
            restrict: 'E',
            replace: true,
            scope: {
                current: '='
            },
        	controller: function($scope){		
		}
	}
})
.directive("pokemon", ['$interval', '$timeout', function($interval, $timeout, $scope, $log,$http){

        return {
            templateUrl:'assets/directives/pokemon.html',
            restrict: 'E',
            replace: true,
            link:function($scope){
            $scope.ballPos={'X':0,'Y':0};
            var tictac, tic=0;
            var level=0;

            $scope.startMove=function(){
                //$log.debug("startMove");

                tictac=$interval(function(){
                    tic++;
                    $scope.ballPos.X=50*Math.sin(tic/50);
                    $scope.ballPos.Y=20*Math.cos(tic/20);
                },30*$scope.speed/10);  
            };

            $scope.stopMove=function(){
                //$log.debug("stopMove");
                $interval.cancel(tictac);
            };

            $scope.speed=0;
            $scope.power=0;
            $scope.size={'width':100,'height':100};
            $scope.name="";
           
      
            $scope.$on('startMove',function(event, data){
                $scope.ballPos={'X':0, 'Y':0};
                tictac=0;
                tic=0;
                //$log.debug("startMoveOn"+data.speed);
                level++;
                //$log.debug(level);
                $scope.speed=data.speed;
                $scope.power=data.power;
                $scope.size={'width':100,'height':100};
                $scope.name=data.name;
                $scope.img=data.image;

                $scope.startMove();
             });

            $scope.$on('stopMove',function(event, data){
                //$log.debug("OnStopMove");
               $scope.stopMove();
             });
            },
            controller: function($scope,$http){

                $scope.clickPokemon=function(){
                    //$log.debug("clickToPokemon");
                    $scope.$emit('changeScore', $scope.power);
                };             
            }
        }
}])