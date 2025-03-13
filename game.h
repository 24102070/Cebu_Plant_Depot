#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int generate(){

srand(time(0));

int answer = (rand()%10)+1;

return answer;
}

void isHigher(int attemptsLeft){

printf("\nYour answer is higher than the correct answer!\nYou have %d attempts left\n", attemptsLeft);

}

void isLower(int attemptsLeft){

printf("\nYour answer is lower than the correct answer!\nYou have %d attempts left\n", attemptsLeft);
    
}

void isEqual(int attemptsDid){

printf("\nCongratulations! You got the correct answer in %d attempts!\n", attemptsDid-1);

}

void outOfAttempts(int answer){

printf("\nYou failed! The correct answer was %d\n", answer);

}