#!/bin/sh

set -x
set -e

function deploy() {
	echo "Starting deploy"
	url=$1
	services=$2
	output=$(curl -s -X POST "${url}/api/deploy" -H "Content-Type: application/json" -d ${services})
	task_id=$(echo $output | grep "task_id" | cut -d '"' -f 8)

	while true; do
		output=$(curl -s -X GET "${url}/api/tasks/${task_id}" -H "Accept: application/json")
		echo $output
		if ! echo $output | grep "Task is still running" >/dev/null; then
			break
		fi
		sleep 5
	done

	echo "Deploy finished"
}

if [ ${BRANCH} = "master" ]; then
	deploy "https://milosm-deploy.vivifyideas.com" 'milosm'
else
	echo "Branch ${BRANCH} is not deployable"
fi

