<?php

    /**
     * @inheritdoc
     */
    function getCustomerId($secupayCustomerId = null, $customerData = [])
    {
        $this->log('debug', __METHOD__ . ' -> args: ' . json_encode(func_get_args()));

        // Return NULL if not data was given.
        if (empty($secupayCustomerId) && empty($customerData['email'])) {
            $this->log('debug', __METHOD__ . ' -> empty');
            return null;
        }

        // We have some data, lets start a search
        try {
            /**
             * @var \SecucardConnect\Product\Payment\CustomersService $service
             */
            $service = $this->getPaymentService('customers');

            // 1) Search by id
            if (!empty($secupayCustomerId)) {
                $customer = $service->get($secupayCustomerId);

                if (!empty($customer->id)) {
                    $this->log('debug', __METHOD__ . ' -> loaded customer by id: ' . json_encode($customer->id));
                    return $customer->id;
                }
            }

            // For the next steps we need a email address, so return NULL if we haven't one.
            if (empty($customerData['email'])) {
                $this->log('debug', __METHOD__ . ' -> empty email');
                return null;
            }

            // The firstname and lastname should not be empty, but to avoid errors set them to NULL in that case.
            $customer_firstname = empty($customerData['firstname']) ? null : $customerData['firstname'];
            $customer_lastname = empty($customerData['lastname']) ? null : $customerData['lastname'];

            // 2) Search by email, first_name, last_name
            $query = new \SecucardConnect\Client\QueryParams();
            $query->fields = ['id'];
            $query->count = 1;
            $query->query = 'email=' . $customerData['email']
                . ' AND forename=' . $customer_firstname
                . ' AND surname=' . $customer_lastname;
            $customers = $service->getList($query);

            if (!empty($customers->count)) {
                $customer = $customers->items[0];

                if (!empty($customer->id)) {
                    $this->log('debug', __METHOD__ . ' -> loaded customer by email: ' . json_encode($customer->id));
                    return $customer->id;
                }
            }

            // 3) Create a new one
            $contact = new Contact();
            $contact->email = $customerData['email'];
            $contact->forename = $customer_firstname;
            $contact->surname = $customer_lastname;

            $customer = new Customer();
            $customer->contact = $contact;

            $customer = $service->save($customer);
            $this->log('debug', __METHOD__ . ' -> created customer id: ' . json_encode($customer->id));

            return $customer->id;
        } catch (\Exception $e) {
            $this->log('debug', $e->getMessage());
        }

        $this->log('debug', __METHOD__ . ' -> null');
        return null;
    }
