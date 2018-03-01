# Lazee mapping

Generate a markdown documentation about your doctrine mapping associations in 2 seconds.

### Prerequisites

- Doctrine
- The namespace of your entities contains ` Entities `

### Install

Import the command file in your project.

### Usage

` bin/console app:lazee-mapping:generate `

### Result

The result is markdown-formatted text about your doctrine association mapping, ready to insert in your github/gitlab project's ` README.md `.

> ### AppBundle\Entity\UserAffiliate
> - `OneToOne` Affiliate : AppBundle\Entity\UserAffiliate est lié à un seul `Affiliate`
> 
> ### AppBundle\Entity\Registration
> - `ManyToOne` Product : AppBundle\Entity\Registration est lié à un seul `Product`
> - `ManyToOne` Customer : AppBundle\Entity\Registration est lié à un seul `Customer`
> - `OneToMany` RegistrationVersion : AppBundle\Entity\Registration est lié à plusieurs `RegistrationVersion`
> - `OneToOne` RegistrationVersion : AppBundle\Entity\Registration est lié à un seul `RegistrationVersion`
> 
> ### AppBundle\Entity\SubCampaign
> - `ManyToOne` Campaign : AppBundle\Entity\SubCampaign est lié à un seul `Campaign`
> - `OneToMany` SubCampaignVersion : AppBundle\Entity\SubCampaign est lié à plusieurs `SubCampaignVersion`
> - `OneToOne` SubCampaignVersion : AppBundle\Entity\SubCampaign est lié à un seul `SubCampaignVersion`
> 
> ### AppBundle\Entity\Admin
> 
> ### AppBundle\Entity\CreationGroup
> - `ManyToOne` Website : AppBundle\Entity\CreationGroup est lié à un seul `Website`
> - `ManyToOne` Canal : AppBundle\Entity\CreationGroup est lié à un seul `Canal`
> - `OneToMany` Creation : AppBundle\Entity\CreationGroup est lié à plusieurs `Creation`
